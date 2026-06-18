@extends('admin.layouts.main')

@section('content')
    <div class="card">
        @if($genres->isEmpty())
            <div class="card-header text-center">
                <p>Belum ada genre yang ditambahkan</p>
                
                <a href="{{ route('admin.genre.create') }}" class="btn btn-primary align-items-center gap-2">
                    <i class="bi bi-pencil-square me-1"></i>Tambah Genre
                </a>
            </div>
        @else
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Daftar Genre</h4>
                <a href="{{ route('admin.genre.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Genre
                </a>
            </div>
            <div class="card-body">
                <div class="card-content">
                    <div class="table-responsive">
                        <table class="table table-lg">
                            <thead>
                                <tr>
                                    <th style="width: 5%">NO</th>
                                    <th style="width: 25%">NAMA GENRE</th>
                                    <th style="width: 40%">DESKRIPSI GENRE</th>
                                    <th style="width: 15%">JUMLAH BUKU</th>
                                    <th style="width: 15%">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($genres as $index => $genre)
                                    <tr>
                                        <td class="text-bold-500">{{ $genres->firstItem() + $index}}</td>
                                        <td class="text-bold-500">{{ $genre->nama }}</td>
                                        <td class="text-bold-200">{{ $genre->deskripsi }}</td>
                                        <td class="text-bold-200">{{ $genre->buku_count }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="{{ route('admin.genre.edit', $genre->idGenre )}}" class="btn btn-sm text-primary" data-bs-toggle="tooltip"title="Edit Genre">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.genre.destroy', $genre->idGenre) }}" onsubmit="displayAlert(event, this, 'Apakah anda ingin menghapus genre {{ $genre->nama }} ?', 'warning')">
                                                    @csrf
                                
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button class="btn btn-sm text-danger" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Genre"><i class="bi bi-trash"></i></button>
                                                </form> 
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mt-3">
                        @if($genres->total() <= 10)
                            <nav class="d-flex align-items-center justify-content-between w-100 mt-4" style="width: 100% !important;">
                                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 w-100">
                                    <div class="small text-center text-sm-start text-muted mb-0">
                                        Menampilkan
                                        <span class="fw-semibold">{{ $genres->firstItem() }}</span>
                                        sampai
                                        <span class="fw-semibold">{{ $genres->lastItem() }}</span>
                                        dari
                                        <span class="fw-semibold">{{ $genres->total() }}</span>
                                        hasil
                                    </div>
                                </div>
                            </nav>
                        @else
                            {{ $genres->links('pagination::bootstrap-5') }}
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
