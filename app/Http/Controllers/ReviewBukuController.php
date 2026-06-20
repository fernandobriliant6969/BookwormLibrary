<?php

namespace App\Http\Controllers;

use App\Models\ReviewBuku;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewBukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idBuku)
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi Input Rating
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'pesan' => ''
        ]);

        // Mendapatkan ID Buku
        $idBuku = $request['idBuku'];

        // Mencari data anggota & buku
        $idUser = Auth::id();
        $buku = Buku::findOrFail($idBuku);

        // Membuat review berdasarkan input
        ReviewBuku::create([
            'idUser' => $idUser,
            'idBuku' => $idBuku,
            'rating' => $request->rating,
            'pesan'  => $request->pesan
        ]);

        // Jika role anggota member, Kembali ke Dashboard Member
        if(Auth::user()->role == 'member'){
            return redirect()->route('member.listbuku')->with('success','Terimakasih sudah memberikan review. Reviewmu akan sangat berati untuk pepustakaan kami');
        // Jika role anggota admin atau superadmin, Kembali ke Dashboard Admin    
        } else {
            return redirect()->route('admin.buku.listbuku')->with('success','Terimakasih sudah memberikan review. Reviewmu akan sangat berati untuk pepustakaan kami');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ReviewBuku $reviewBuku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReviewBuku $reviewBuku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idReview)
    {
        // Validasi Input
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'pesan' => ''
        ]);

        // Mencari review menggunakan ID Review
        $review = ReviewBuku::findOrFail($idReview);

        // Mengupdate Review berdasarkan input validasi
        $review->update([
            'rating' => $request->rating,
            'pesan'  => $request->pesan
        ]);

        // Jika role anggota member, Kembali ke Dashboard Member
        if(Auth::user()->role == 'member'){
            return redirect()->route('member.listbuku')->with('success','Review berhasil diupdate');
        // Jika role anggota admin atau superadmin, Kembali ke Dashboard Admin    
        } else {
            return redirect()->route('admin.buku.listbuku')->with('success','Review berhasil diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idReview)
    {
        // Mencari review menggunaakan ID Review
        $review = ReviewBuku::findOrFail($idReview);

        // Menghapus review
        $review->delete();

        // Jika role anggota member, Kembali ke Dashboard Member
        if(Auth::user()->role == 'member'){
            return redirect()->route('member.listbuku')->with('success','Berhasil menghapus rating');
        } else {
        // Jika role anggota admin atau superadmin, Kembali ke Dashboard Admin    
            return redirect()->route('admin.buku.listbuku')->with('success','Berhasil menghapus rating');
        }
    }
}
