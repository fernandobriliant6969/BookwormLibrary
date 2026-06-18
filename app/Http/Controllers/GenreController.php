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
        $input = $request->validate([
            'nama' => 'required|unique:genres',
            'deskripsi' => 'required'
        ], [
            'nama.required' => 'Nama genre harus di isi',
            'deskripsi.required' => 'Deskripsi genre harus di isi',
            'nama.unique' => 'Nama genre ini sudah ada, gunakan nama lain'
        ]);

        $genre = Genre::create($input);

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
        $input = $request->validate([
            'nama' => 'required|unique:genres,nama,' . $idGenre . ',idGenre',
            'deskripsi' => 'required',
        ], [
            'nama.unique' => 'Nama genre ini sudah ada, gunakan nama lain!',
            'nama.required' => 'Nama genre harus di isi!',
            'deskripsi.required' => 'Deskripsi genre harus di isi!',
        ]);

        $genre = Genre::find($idGenre);

        $genre->update($input);

        return redirect()->route('admin.genre.index')->with('success', 'Berhasil update genre dengan nama ' . $genre->nama . '');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        $genres = Genre::find($genre);
        if($genre){
            $genre->delete();
            return redirect()->route('admin.genre.index')->with('success', 'Berhasil menghapus genre!');
        }
    }
}
