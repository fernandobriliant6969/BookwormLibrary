<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genres = Genre::withCount('buku')->paginate(10);
        return view('admin.genre.index', compact('genres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.genre.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $input = $request->validate([
            'nama' => 'required|unique:genres',
            'deskripsi' => 'required'
        ], [
            'nama.required' => 'Nama genre harus di isi',
            'deskripsi.required' => 'Deskripsi genre harus di isi',
            'nama.unique' => 'Nama genre ini sudah ada, gunakan nama lain'
        ]);

        // Membuat genre
        $genre = Genre::create($input);

        // Mengembalikan pengguna ke list genre
        return redirect()->route('admin.genre.index')->with('success', 'Berhasil menambahkan genre dengan nama ' . $genre->nama . '');
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Genre $genre)
    {
        return view('admin.genre.edit', compact('genre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idGenre)
    {
        // Validasi Input
        $input = $request->validate([
            'nama' => 'required|unique:genres,nama,' . $idGenre . ',idGenre',
            'deskripsi' => 'required',
        ], [
            'nama.unique' => 'Nama genre ini sudah ada, gunakan nama lain!',
            'nama.required' => 'Nama genre harus di isi!',
            'deskripsi.required' => 'Deskripsi genre harus di isi!',
        ]);

        // Mencari genre berdasarkan id genre yang ingin di update
        $genre = Genre::findOrFail($idGenre);

        // Mengupdate genre dengan data input baru
        $genre->update($input);

        // Mengembalikan pengguna ke list genre
        return redirect()->route('admin.genre.index')->with('success', 'Berhasil update genre dengan nama ' . $genre->nama . '');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idGenre)
    {
        $genre = Genre::findOrFail($idGenre);
        if($genre){
            $genre->delete();
            return redirect()->route('admin.genre.index')->with('success', 'Berhasil menghapus genre!');
        }
    }
}
