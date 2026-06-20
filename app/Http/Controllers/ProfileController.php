<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
Use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $idUser)
    { 
        // Mencari anggota yang ingin di update profilenya
        $user = User::findOrFail($idUser);

        // Validasi input
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

        // Jika anggota klik Button Hapus Avatar
        if ($request->is_avatar_deleted == '1') {

            // Jika anggota mempunyai avatar
            if ($user->photoUrl) {
                $publicId = pathinfo($user->photoUrl, PATHINFO_FILENAME);

                // Menghapus avatar yang di cloudinary
                $uploadApi->destroy('user/' . $publicId);
                $input['photoUrl'] = null;
            }
        
        // Jika anggota upload avatar baru
        } elseif ($request->hasFile('avatar')) {
            
            // Jika anggota mempunyai avatar
            if ($user->photoUrl) {
                $oldPublicId = pathinfo($user->photoUrl, PATHINFO_FILENAME);

                // Menghapus avatar lama
                $uploadApi->destroy('user/' . $oldPublicId);
            }
            
            // Mengupload avatar baru ke cloudinary
            $upload = $uploadApi->upload($request->file('avatar')->getRealPath(), [
                'folder' => 'user'
            ]);
            
            // Menyimpan link avatar ke input
            $input['photoUrl'] = $upload['secure_url'];            
        }

        // Mengupdate profile anggota berdasarkan input
        $user->update([
            "nama" => $input['nama'],
            "username" => $input['username'],
            "nomorTelp" => $input['nomorTelp'],
            "jenisKelamin" => $input['jenisKelamin'],
            "alamat" => $input['alamat'],
            "photoUrl" => $input['photoUrl'] 
        ]);

        return redirect()->route('profile.edit')->with('success','Berhasil mengupdate profile');
    }

    /**
     * Delete the user's account.
     */

    // Ga Dipakai Methodnya
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
