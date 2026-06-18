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
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'pesan' => ''
        ]);

        $idBuku = $request['idBuku'];

        $idUser = Auth::id();
        $buku = Buku::findOrFail($idBuku);

        ReviewBuku::create([
            'idUser' => $idUser,
            'idBuku' => $idBuku,
            'rating' => $request->rating,
            'pesan'  => $request->pesan
        ]);

        if(Auth::user()->role == 'member'){
            return redirect()->route('member.listbuku')->with('success','Terimakasih sudah memberikan review. Reviewmu akan sangat berati untuk pepustakaan kami');
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
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'pesan' => ''
        ]);

        $review = ReviewBuku::findOrFail($idReview);

        $review->update([
            'rating' => $request->rating,
            'pesan'  => $request->pesan
        ]);

        if(Auth::user()->role == 'member'){
            return redirect()->route('member.listbuku')->with('success','Review berhasil diupdate');
        } else {
            return redirect()->route('admin.buku.listbuku')->with('success','Review berhasil diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idReview)
    {
        $review = ReviewBuku::findOrFail($idReview);

        $review->delete();

        if(Auth::user()->role == 'member'){
            return redirect()->route('member.listbuku')->with('success','Berhasil menghapus rating');
        } else {
            return redirect()->route('admin.buku.listbuku')->with('success','Berhasil menghapus rating');
        }
    }
}
