@extends('admin.layouts.main')

@section('content')
    <div class="card">
        <!-- Jika belum ada genre yang belum ditambahkan -->
        @if($genres->isEmpty())
            <div class="card-header text-center">
                <p>Belum ada genre yang ditambahkan</p>
                
                <a href="{{ route('admin.genre.create') }}" class="btn btn-primary align-items-center gap-2">
                    <i class="bi bi-pencil-square me-1"></i>Tambah Genre
                </a>
            </div>
        <!-- Jika ada data genre, tampilkan data genre dalam bentuk tabel -->
        @else
            <!-- Card Header -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <!-- Judul Halaman -->
                <h4 class="card-title">Daftar Genre</h4>

                <!-- Button untuk Tambah Genre -->
                <a href="{{ route('admin.genre.create') }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square me-1"></i>Tambah Genre
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
                                <!-- Menampilkan tiap genre -->
                                @foreach($genres as $index => $genre)
                                    <tr>
                                        <!-- Sistem Penomoran Baris -->
                                        <td class="text-bold-500">{{ $genres->firstItem() + $index}}</td>

                                        <!-- Nama Genre -->
                                        <td class="text-bold-500">{{ $genre->nama }}</td>
                                        
                                        <!-- Deskripsi Genre -->
                                        <td class="text-bold-200">{{ $genre->deskripsi }}</td>

                                        <!-- Jumlah Buku yang menggunakan Genre tersebut -->
                                        <td class="text-bold-200">{{ $genre->buku_count }}</td>

                                        <!-- Menu Button -->
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- Button Edit Genre -->
                                                <a href="{{ route('admin.genre.edit', $genre->idGenre )}}" class="btn btn-sm text-primary" data-bs-toggle="tooltip"title="Edit Genre">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <!-- Button Hapus Genre -->
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
                        <!-- Jika data genre kurang dari 10, Gunakan paginate manual  "Menampilkan 1 sampai 10 dari 10 hasi" sebagai contoh untuk memberitahu berapa banyak genre yang ditampilkan -->
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
                        <!-- Jika data genre lebih dari 10, gunakan paginate bawaan laravel -->
                        @else
                            {{ $genres->links('pagination::bootstrap-5') }}
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
