<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Mencari anggota berdasarkan email atau nama atau username
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($sub) use ($search) {
                $sub->where('email', 'like', '%' . $search . '%')->orWhere('nama', 'like', '%' . $search . '%')->orWhere('username', 'like', '%' . $search . '%');
            });
        });

        // Filter by role
        $query->when($request->filled('role'), function ($q) use ($request) {
            $search = $request->role;
            $q->where(function ($sub) use ($search) {
                $sub->where('role', $search);
            });
        });

        // Menampilkan hasil filter
        $users = $query->paginate(10)->appends($request->all());

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nama" => "required",
            "password" => ["required", Rules\Password::defaults()]
        ],[
            "nama.required" => "Nama harus di isi",
            "password.required" => "Password harus di isi",
            "password.min" => "Minimal panjang password 8 karakter"
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'nomorTelp' => $request->nomorTelp,
            'jenisKelamin' => $request->jenisKelamin,
            'alamat' => $request->alamat,
            'role' => 'member',
            'status' => 'active',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.user.index')->with('success','Berhasil menambahkan anggota dengan nama ' . $user->nama);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($idUser)
    {
        $user = User::findOrFail($idUser);

        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idUser)
    {
        $user = User::findOrFail($idUser);

        $input = $request->validate([
            "nama" => "required",
            "username" => "required|unique:users,username," . $user->idUser . ",idUser",
            "nomorTelp" => "required",
            "jenisKelamin" => "required",
            "alamat" => "required"
        ],[
            "nama.required" => "Nama harus di isi",
            "username.required" => "Username harus di isi",
            "username.unique" => "Username ini sudah digunakan, Gunakan username lain",
            "nomorTelp.required" => "Nomor telepon harus di isi",
            "jenisKelamin.required" => "Jenis kelamin harus di isi",
            "alamat.required" => "Alamat harus di isi"
        ]);

        // Memanggil Cloudinary
        Configuration::instance();
        $uploadApi = new UploadApi();

        $input['photoUrl'] = $user->photoUrl;

        if ($request->is_avatar_deleted == '1') {
            if ($user->photoUrl) {
                $publicId = pathinfo($user->photoUrl, PATHINFO_FILENAME);
                $uploadApi->destroy($publicId);
                $input['photoUrl'] = null;
            }
        } elseif ($request->hasFile('avatar')) {
            
            if ($user->photoUrl) {
                $oldPublicId = pathinfo($user->photoUrl, PATHINFO_FILENAME);
                $uploadApi->destroy($oldPublicId);
            }
            
            $uploadResponse = $uploadApi->upload($request->file('avatar')->getRealPath(), [
                'folder' => 'user'
            ]);
            
            $newPhotoUrl = $uploadResponse['secure_url'];
            
            $input['photoUrl'] = $newPhotoUrl;
        }

        $user->update([
            "nama" => $input['nama'],
            "username" => $input['username'],
            "nomorTelp" => $input['nomorTelp'],
            "jenisKelamin" => $input['jenisKelamin'],
            "alamat" => $input['alamat'],
            "photoUrl" => $input['photoUrl'] 
        ]);

        return redirect()->route('admin.user.index')->with('success','Berhasil mengupdate user dengan nama ' . $user->nama);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idUser)
    {
        // Cegah jika user dengan role member mencoba menghapus user lain
        if(Auth::user()->role == 'member'){
            return redirect()->route('admin.user.index')->with('failed','Kamu tidak mempunyai akses untuk menghapus user');
        }
    
        // Cegah untuk menghapus akun diri sendiri
        if(Auth::id() == $idUser){
            return redirect()->route('admin.user.index')->with('failed','Tidak bisa menghapus akun diri sendiri');
        }

        // Mencari user target apakah ada datanya di database
        $user = User::findOrFail($idUser);

        // Sesama admin tidak bisa menghapus user dengan role admin ataupun superadmin
        if(Auth::user()->role !== 'superadmin' && ($user->role == 'admin' || $user->role == 'superadmin')){
            return redirect()->route('admin.user.index')->with('failed','Tidak bisa menghapus akun admin atau superadmin');
        }

        // Jika user yang menghapus memiliki role superadmin, maka hapus usernya
        $user->delete();

        // Mengembalikan ke user index dengan pesan bahwa user berhasil dihapus
        return redirect()->route('admin.user.index')->with('success','Berhasil menghapus user');
    }

    /**
     * Method untuk membuat user menjadi admin
     */
    public function makeAdmin($idUser)
    {
        $user = User::findOrFail($idUser);

        if(Auth::id() == $idUser || $user->role == 'admin' || $user->role == 'superadmin'){
            return redirect()->route('admin.user.index')->with('failed','Gagal mengupdate user menjadi admin');
        }

        $user->update(['role' => 'admin']);

        return redirect()->route('admin.user.index')->with('success','Berhasil mengupdate role user menjadi admin');
    }

    /**
     * Method untuk membuat user menjadi member
     */
    public function makeMember($idUser)
    {
        $user = User::findOrFail($idUser);

        if(Auth::id() == $idUser){
            return redirect()->route('admin.user.index')->with('failed','Gagal mengupdate user menjadi admin');
        }

        if($user->role == 'member'){
            return redirect()->route('admin.user.index')->with('failed','User sudah menjadi member');
        }

        $user->update(['role' => 'member']);

        return redirect()->route('admin.user.index')->with('success','Berhasil mengupdate role user menjadi member');
    }
}
