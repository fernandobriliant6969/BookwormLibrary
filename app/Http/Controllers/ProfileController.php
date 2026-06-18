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
                $uploadApi->destroy('user/' . $publicId);
                $input['photoUrl'] = null;
            }
        } elseif ($request->hasFile('avatar')) {
            
            if ($user->photoUrl) {
                $oldPublicId = pathinfo($user->photoUrl, PATHINFO_FILENAME);
                $uploadApi->destroy('user/' . $oldPublicId);
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

        return redirect()->route('profile.edit')->with('success','Berhasil mengupdate profile');
    }

    /**
     * Delete the user's account.
     */
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
