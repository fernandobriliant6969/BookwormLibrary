<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Genre;
use Illuminate\Http\Request;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

use Illuminate\Support\Facades\Http;

class BukuController extends Controller
{    

    // Proxy cover image dari cloudinary untuk memghubungkan otomatis dengan filepond
    public function proxyCover(Request $request)
    {
        // Mendapatkan URl dari Cover Buku (Jika ada)
        $url = $request->query('url');
        
        if (!$url) {
            return response('URL tidak ditemukan', 404);
        }

        // Ambil gambar dari Cloudinary secara aman lewat backend
        $response = Http::get($url);

        if ($response->failed()) {
            return response('Gagal mengambil gambar', 500);
        }

        // Kembalikan sebagai file gambar asli ke FilePond
        return response($response->body(), 200)->header('Content-Type', $response->header('Content-Type'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {        
        $query = Buku::with('genre');

        // Mencari buku berdasarkan judul atau pengarang
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($sub) use ($search) {
                $sub->where('judul', 'like', '%' . $search . '%')->orWhere('pengarang', 'like', '%' . $search . '%');
            });
        });

        // Filter by genre dan bisa banyak genre
        $query->when($request->filled('idGenre') && is_array($request->idGenre), function ($q) use ($request) {
            $selectedGenres = array_filter($request->idGenre); 

            if (!empty($selectedGenres)) {
                $q->whereHas('genre', function ($sub) use ($selectedGenres) {
                    $sub->whereIn('genres.idGenre', $selectedGenres);
                });
            }
        });

        // Menampilkan hasil filter
        $bukus = $query->paginate(10)->appends($request->all());
        
        // Mengambil semua genre
        $genres = Genre::all();

        return view('admin.buku.index', compact('bukus', 'genres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genre = Genre::all();
        return view('admin.buku.create', compact('genre'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari user
        $input = $request->validate([
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tanggalTerbit' => 'required',
            'jumlahHalaman' => 'required',
            'idGenre' => 'required|array',
            'idGenre.*' => 'integer|exists:genres,idGenre',
            'status'=> 'required',
            'stok' => 'required|integer|min:0'
        ], [
            'judul.required' => 'Judul buku harus di isi',
            'pengarang.required' => 'Pengarang buku harus di isi',
            'penerbit.required' => 'Penerbit buku harus di isi',
            'tanggalTerbit.required' => 'Tanggal terbit buku harus di isi',
            'jumlahHalaman.required' => 'Jumlah halaman buku harus di isi',
            'idGenre.required' => 'Pilih minimal 1 genre untuk buku ini',   
            'status.required' => 'Status buku harus di isi',
            'stok.required' => 'Stok buku harus di isi',
            'stok.integer' => 'Stok buku harus berupa angka',
            'stok.min' => 'Minimal stok tidak boleh kurang dari 0'
        ]);


        // Ambil judul buku dari input
        $judul = $input['judul'];
        $pengarang = $input['pengarang']; 

        $prompt = <<<EOT
        Berikan ringkasan atau sinopsis singkat dalam Bahasa Indonesia untuk buku berjudul "$judul" karya "$pengarang". 

        Ketentuan:
        1. Langsung berikan isi ringkasannya saja dalam 4-6 kalimat tanpa kalimat basa-basi pembuka (seperti "Tentu, ini ringkasannya") atau penutup.
        2. Jika benar-benar tidak tahu sama sekali tentang buku ini (judul asal ketik), cukup jawab: "Tidak ada informasi yang tersedia untuk buku ini."
        EOT;

        try {
            $response = Http::withToken(env('GROQ_API_KEY'))->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile', 
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);

            $resArray = $response->json();

            if (isset($resArray['choices'][0]['message']['content'])) {
                $hasilSummary = $resArray['choices'][0]['message']['content'];
            } else {
                $hasilSummary = null;
            }
            
        } catch (\Exception $e) {
            $hasilSummary = null;
        }

        // Jika stok buku 0, otomais set status buku menjadi tidak tersedia
        if($input['stok'] == 0){
            $input['status'] = 'tidak tersedia';
        }

        // Mengsortir data input ke database
        $buku = Buku::create([
            'judul' => $input['judul'],
            'pengarang' => $input['pengarang'],
            'penerbit' => $input['penerbit'],
            'tanggalTerbit' => $input['tanggalTerbit'],
            'jumlahHalaman' => $input['jumlahHalaman'],
            'status' => $input['status'],
            'stok' => $input['stok']
        ]);

        // Cek apakah user menambahkan photo buku? jika ditambahkan maka upload via cloudinary
        if($request->hasFile('photoUrl')){
            // Memanggil Cloudinary
            Configuration::instance();

            // Mengupload file ke cloudinary
            $update = (new UploadApi())->upload($request->file('photoUrl')->getRealPath(), [
                'folder' => 'buku'
            ]);

            // Ambil URL Gambar dari Cloudinary
            $photoUrl = $update['secure_url'];

            // Menyimpan URL Gambar dari Cloudinary ke Database
            $buku->update([
                'photoUrl' => $photoUrl
            ]);
        }
        
        if($hasilSummary){
            $buku->update([
                'ringkasan' => $hasilSummary
            ]);
        }

        // Memasukkan id genre ke dalam tabel relasi antara buku dan genre
        $genre = $request->input('idGenre');
        $buku->genre()->attach($genre);

        // Kembalikan user ke index dan beritahu bahwa buku yang ditambahkan telah berhasil
        return redirect()->route('admin.buku.index')->with('success', 'Berhasil menambahkan buku dengan nama ' . $buku->judul);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($idBuku)
    {
        $buku = Buku::findOrFail($idBuku);
        $genre = Genre::all();
        return view('admin.buku.edit', compact('buku', 'genre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idBuku)
    {
        // Cek apakah id buku yang di update ada di database?
        $buku = Buku::findOrFail($idBuku);

        // Validasi input dari user
        $input = $request->validate([
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tanggalTerbit' => 'required',
            'jumlahHalaman' => 'required',
            'idGenre' => 'required|array',
            'idGenre.*' => 'integer|exists:genres,idGenre',
            'status'=> 'required',
            'stok'=> 'required|integer|min:0'
        ], [
            'judul.required' => 'Judul buku harus di isi',
            'pengarang.required' => 'Pengarang buku harus di isi',
            'penerbit.required' => 'Penerbit buku harus di isi',
            'tanggalTerbit.required' => 'Tanggal terbit buku harus di isi',
            'jumlahHalaman.required' => 'Jumlah halaman buku harus di isi',
            'idGenre.required' => 'Pilih minimal 1 genre untuk buku ini',   
            'status.required' => 'Status buku harus di isi',
            'stok.required' => 'Stok buku harus di isi',
            'stok.integer' => 'Stok buku harus berupa angka',
            'stok.min' => 'Minimal stok tidak boleh kurang dari 0'
        ]);

        if((strtolower($buku->judul) != strtolower($input['judul'])) && (strtolower($buku->pengarang) != strtolower($input['pengarang']))){
            // Ambil judul buku dari input
            $judul = $input['judul'];
            $pengarang = $input['pengarang']; 

            $prompt = <<<EOT
            Berikan ringkasan atau sinopsis singkat dalam Bahasa Indonesia untuk buku berjudul "$judul" karya "$pengarang". 

            Ketentuan:
            1. Langsung berikan isi ringkasannya saja dalam 4-6 kalimat tanpa kalimat basa-basi pembuka (seperti "Tentu, ini ringkasannya") atau penutup.
            2. Jika benar-benar tidak tahu sama sekali tentang buku ini (judul asal ketik), cukup jawab: "Tidak ada informasi yang tersedia untuk buku ini."
            EOT;

            try {
                $response = Http::withToken(env('GROQ_API_KEY'))->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.3-70b-versatile', 
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                ]);

                $resArray = $response->json();

                if (isset($resArray['choices'][0]['message']['content'])) {
                    $hasilSummary = $resArray['choices'][0]['message']['content'];
                } else {
                    $hasilSummary = null;
                }
                
            } catch (\Exception $e) {
                $hasilSummary = null;
            }
        } else {
            $hasilSummary = $buku->ringkasan;
        }

        // Jika stok buku 0, otomais set status buku menjadi tidak tersedia
        if($input['stok'] == 0){
            $input['status'] = 'tidak tersedia';
        }

        // Mengsortir data input ke database
        $dataUpdate = [
            'judul' => $input['judul'],
            'pengarang' => $input['pengarang'],
            'penerbit' => $input['penerbit'],
            'tanggalTerbit' => $input['tanggalTerbit'],
            'jumlahHalaman' => $input['jumlahHalaman'],
            'status' => $input['status'],
            'stok' => $input['stok'],
            'ringkasan' => $hasilSummary
        ];

        // Memanggil cloudinary
        Configuration::instance();
        $uploadApi = new UploadApi();
        // 1. Jika user upload foto cover buku baru
        if ($request->hasFile('photoUrl')) {
            // Hapus cover buku lama
            $oldPublicId = pathinfo($buku->photoUrl, PATHINFO_FILENAME);
            $uploadApi->destroy('buku/' . $oldPublicId);

            // Upload cover buku baru
            $update = $uploadApi->upload($request->file('photoUrl')->getRealPath(), [
                'folder' => 'buku'
            ]);
            $dataUpdate['photoUrl'] = $update['secure_url'];
        // 2. Jika user hanya update data buku selain cover buku
        } else if ($request->has('photoUrl') && !empty($request->input('photoUrl'))) {
            $dataUpdate['photoUrl'] = $request->input('photoUrl');
        // 3. Jika user menghapus cover buku dan tidak menambahkan cover baru
        } else { //
            $dataUpdate['photoUrl'] = null;

            $oldPublicId = pathinfo($buku->photoUrl, PATHINFO_FILENAME);
            $uploadApi->destroy('buku/' . $oldPublicId);
        }

        // Mengupdate data buku
        $buku->update($dataUpdate);

        // Mengsinkron id genre ke dalam tabel relasi antara buku dan genre
        $buku->genre()->sync($request->idGenre);

        // Kembalikan user ke index dan beritahu bahwa buku yang ditambahkan telah berhasil
        return redirect()->route('admin.buku.index')->with('success', 'Berhasil mengupdate buku dengan nama ' . $buku->judul);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idBuku)
    {
        $buku = Buku::findOrFail($idBuku);

        $namaBuku = $buku->judul;

        $buku->delete();

        return redirect()->route('admin.buku.index')->with('success','Berhasil menghapus buku ' . $namaBuku);
    }

    public function listbuku(Request $request)
    {
        $query = Buku::with([
            'genre', 
            'review' => function($query) {
                $query->latest();
            }
        ])->withCount('review')->withAvg('review as rating_avg', 'review_bukus.rating');

        // Mencari buku berdasarkan judul atau pengarang
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($sub) use ($search) {
                $sub->where('judul', 'like', '%' . $search . '%')->orWhere('pengarang', 'like', '%' . $search . '%');
            });
        });

        // Filter by genre dan bisa banyak genre
        $query->when($request->filled('idGenre') && is_array($request->idGenre), function ($q) use ($request) {
            $selectedGenres = array_filter($request->idGenre); 

            if (!empty($selectedGenres)) {
                $q->whereHas('genre', function ($sub) use ($selectedGenres) {
                    $sub->whereIn('genres.idGenre', $selectedGenres);
                });
            }
        });

        // Menampilkan hasil filter
        $bukus = $query->paginate(24)->appends($request->all());
        
        // Mengambil semua genre
        $genres = Genre::all();

        return view('admin.buku.list', compact('bukus', 'genres'));
    }

}
