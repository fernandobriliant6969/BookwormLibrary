@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bukuDisplay.css') }}">
    <style>
        .ringkasan {
            overflow: hidden;
            scrollbar-width: none !important; /* Firefox */
            -ms-overflow-style: none !important;  /* IE and Edge */
        }

        /* Untuk responsive card buku agar tidak gepeng dan pecah ketika buka di hp */
        .card-img-responsive {
            height: 230px;
        }

        @media (min-width: 768px) {
            .card-img-responsive {
                height: 300px;
            }
        }

        @media (min-width: 992px) {
            .card-img-responsive {
                height: 400px;
            }
        }
    </style>
@endpush

<!-- Memberikan keterangan "List Buku" pada Judul Halaman -->
@section('current-page','List Buku')

@section('content')
    <div class="card">
        <div class="card-body border-bottom border-secondary pb-3">

            <!-- Form Cari Buku menggunakan Juduk / Nama Pengarang dan Filter menggunakan Genre -->
            <form action="{{ route('member.listbuku') }}" method="GET" id="form-filter-buku" onsubmit="tampilLoadingAnimation(this)">
                <div class="row g-3 align-items-end">
                    <!-- Input Cari Buku Menggunakan Judul / Nama Pengarang -->
                    <div class="col-md-5 col-12">
                        <label class="form-label fw-bold small mb-1">Cari Buku menggunakan Judul/Author</label>
                        
                        <div class="input-group">                            
                            <input type="text" class="form-control border-secondary text-white custom-search-input" id="search-buku" name="search" value="{{ request('search') }}" placeholder="Ketik judul buku atau nama pengarang...">
                        </div>
                    </div>

                    <!-- Filter Buku menggunakan Genre dan Bisa banyak genre -->
                    <div class="col-md-4 col-12">
                        <label class="form-label fw-bold small mb-1">Tampil buku berdasarkan kategori</label>
            
                        <select class="choices form-select" name="idGenre[]" id="filter-genre" multiple="multiple">
                            @foreach($genres as $g)
                                <option value="{{ $g->idGenre }}" {{ is_array(request('idGenre')) && in_array($g->idGenre, request('idGenre')) ? 'selected' : '' }}>
                                    {{ $g->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 col-12">
                        <label class="form-label d-none d-md-block">&nbsp;</label> 

                        <div class="d-flex gap-2 w-100">
                            <!-- Button untuk Cari Buku -->
                            <button type="submit" class="btn btn-success w-50 align-items-center gap-2">
                                <span id="text-button">
                                    <i class="bi bi-search me-1"></i> Cari
                                </span>
                                <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                <span id="text-loading" class="d-none">Loading...</span>
                            </button>
                            
                            <!-- Jika sudah pencet Cari dan ada input cari buku atau filter, maka button Reset dapat digunakan (Fungsinya untuk membersihkan input dan filter) -->
                            @if(request('search') || request('idGenre'))
                                <a href="{{ route('member.listbuku') }}" class="btn btn-danger text-white w-50 align-items-center gap-2">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                </a>
                            <!-- Jika belum input / filter (Tidak melakukan pencarian maka button reset di matikan) -->
                            @else
                                <button type="button" class="btn btn-secondary w-50 align-items-center gap-2 disabled" style="opacity: 0.5;">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Jika data buku masih kosong -->
        @if($bukus->isEmpty())
            <div class="card-body text-center py-5">
                <i class="bi bi-journal-bookmark text-muted" style="font-size: 3rem;"></i>
                <p class="mt-2 text-white">Belum ada buku yang ditambahkan</p>
            </div>
        <!-- Jika ada, maka tampilkan dalam bentuk card per buku -->
        @else
            <div class="card mt-2">
                <div class="card-body">
                    <div class="row g-4">
                        @foreach($bukus as $buku)

                            <!-- di Laptop/PC 1 baris isi 4 buku, di Tablet 1 baris isi 3 Buku dan di HP 1 baris isi 2 buku -->
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card h-100 shadow-lg border-0">
                                    <!-- Menampilkan cover buku (Jika ada cover, gunakan Covernya. Jika tidak ada, Gunakan template cover) dan trigger modal ketika cover buku diklik -->
                                    <div style="width: 100%; overflow: hidden; background-color: #1e1e2d; cursor: pointer; display: block; cursor: pointer;" class="rounded-top card-img-responsive" data-bs-toggle="modal" data-bs-target="#detailBukuModal-{{ $buku->idBuku }}">
                                        <img src="{{ $buku->photoUrl ?? "https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=200&auto=format&fit=crop&q=60" }}" class="w-100 h-100 rounded" style="object-fit: fill; display: block;" alt="{{ $buku->judul }}">
                                    </div>

                                    <!-- Keterangan / Deskripsi Buku di bawah Cover Buku -->
                                    <div class="card-body p-2 d-flex flex-column justify-content-between">
                                        <div>
                                            <!-- Menampilkan Judul Buku -->
                                            <h6 class="card-title text-truncate mb-1" title="{{ $buku->judul }}" style="font-size: 0.9rem;" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $buku->judul }}">
                                                {{ $buku->judul }}
                                            </h6>
                                            
                                            <!-- Menampilkan Pengarang Buku -->
                                            <p class="text-muted text-truncate mb-3" style="font-size: 0.8rem; font-weight: 500;" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $buku->pengarang }}">
                                                <i class="bi bi-person me-1"></i>{{ $buku->pengarang }} 
                                            </p>
                                            
                                            <!-- Menampilkan Genre menggunakan Badge -->
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($buku->genre as $genre)
                                                    <span class="badge bg-light-primary text-primary text-xs mb-1">
                                                        {{ $genre->nama }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Menampilkan rating dan jumlah review -->
                                        <div class="mt-1 border-top d-flex align-items-center" style="font-size: 0.8rem;">
                                            <i class="bi bi-star-fill text-warning me-1 mt-0"></i>
                                            <span class="text-muted pt-2" style="font-size: 0.7rem;">{{ number_format($buku->rating_avg, 1) ?? '0.0' }} ({{ $buku->review_count }})</span>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <!-- Trigger modal ketika cover buku diklik -->
                            <div class="modal fade" id="detailBukuModal-{{ $buku->idBuku }}" tabindex="-1" aria-labelledby="detailBukuModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content border-0 shadow">
                                        
                                        <!-- Menampilkan Judul Modal : "Detail Buku" -->
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title text-body fs-5" id="detailBukuModalLabel">Detail Buku</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        
                                        <!-- Menampilkan Isi Modal -->
                                        <div class="modal-body">
                                            <div class="row g-4 g-lg-5 align-items-start">
                                                
                                                <!-- Menampilkan Cover Buku, Jika ada cover yang digunakan. Gunakan cover nya, Jika tidak ada cover yang ditambahkan, Gunakan template cover -->
                                                <div class="col-12 col-lg-4 text-center">
                                                    <div class="mx-auto rounded shadow-sm overflow-hidden bg-light" style="width: 100%; max-width: 280px; aspect-ratio: 2/3;">
                                                        <img src="{{ $buku->photoUrl ?? "https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=200&auto=format&fit=crop&q=60" }}" class="w-100 h-100" style="object-fit: fill;" alt="{{ $buku->judul }}">
                                                    </div>
                                                </div>

                                                <!-- Menampilkan Detail Buku menggunakan Table -->
                                                <div class="col-12 col-lg-8">
                                                    <div class="table-responsive">
                                                        <table class="table table-borderless m-0 fs-6 fs-lg-5">
                                                            <tbody>
                                                                <!-- Menampilkan Judul Buku -->
                                                                <tr>
                                                                    <td class="text-secondary fw-bold pb-2" style="width: 30%;">Judul</td>
                                                                    <td class="pb-2 text-body fw-semibold">{{ $buku->judul }}</td>
                                                                </tr>
                                                                
                                                                <!-- Menampilkan Pengarang Buku -->
                                                                <tr>
                                                                    <td class="text-secondary fw-bold pb-2">Author</td>
                                                                    <td class="pb-2 text-body">{{ $buku->pengarang }}</td>
                                                                </tr>

                                                                <!-- Menampilkan Penerbit Buku -->
                                                                <tr>
                                                                    <td class="text-secondary fw-bold pb-2">Penerbit</td>
                                                                    <td class="pb-2 text-body">{{ $buku->penerbit }}</td>
                                                                </tr>
                                                                
                                                                <!-- Menampilkan Tanggal Terbit dan Menggunakan Carbon untuk mengonversi tanggal menggunakan format (d M Y) / (tanggal bulan Tahun) -->
                                                                <tr>
                                                                    <td class="text-secondary fw-bold pb-2">Tanggal Terbit</td>
                                                                    <td class="pb-2 text-body">
                                                                        {{ \Carbon\Carbon::parse($buku->tanggalTerbit)->translatedFormat('d F Y') }}
                                                                    </td>
                                                                </tr>
                                                                
                                                                <!-- Menampilkan Jumlah Halaman Buku -->
                                                                <tr>
                                                                    <td class="text-secondary fw-bold pb-2">Jumlah Halaman</td>
                                                                    <td class="pb-2 text-body">{{ $buku->jumlahHalaman }} Halaman</td>
                                                                </tr>
                                                                
                                                                <!-- Menampilkan Genre menggunakan Badge -->
                                                                <tr>
                                                                    <td class="text-secondary fw-bold pb-2">Genre</td>
                                                                    <td class="pb-2">
                                                                        <div class="d-inline-flex flex-wrap gap-2">
                                                                            @if($buku->genre)
                                                                                @foreach($buku->genre as $genre)
                                                                                    <span class="badge bg-light-primary text-primary text-xs">
                                                                                        {{ $genre->nama }}
                                                                                    </span>
                                                                                @endforeach
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                
                                                                <!-- Menampilkan Rating -->
                                                                <tr>
                                                                    <td class="text-secondary fw-bold pb-2">Rating</td>
                                                                    <td class="pb-2 text-body">
                                                                        <span class="fw-bold text-warning-custom">
                                                                            ⭐
                                                                            {{ number_format($buku->rating_avg, 1) ?? '0.0' }}
                                                                            <span class="text-secondary fw-normal text-xs">/ 5.0</span>
                                                                        </span>
                                                                    </td>
                                                                </tr>

                                                                <!-- Menampikan Ringkasan -->
                                                                <tr>
                                                                    <td colspan="2" class="pt-3 pb-0">
                                                                        <div class="rounded-4 p-3 border border-primary border-opacity-25" style="background-color: rgba(67, 94, 190, 0.08); width: 100%;">
                                                                            <div class="d-flex align-items-center mb-2 text-primary fw-bold" style="font-size: calc(0.85rem + 0.15vw); line-height: 1;">
                                                                                <i class="bi bi-cpu-fill me-3 fs-5 d-inline-flex align-items-center"></i> 
                                                                                <span>Ringkasan - AI Summary</span>
                                                                            </div>
                                                                            
                                                                            <p class="mb-0 text-primary text-opacity-75 small ringkasan" style="text-align: justify; line-height: 1.6; max-height: 150px; overflow-y: auto;">
                                                                                <!-- Jika ada Ringkasan yang dihasilkan -->
                                                                                @if(!blank($buku->ringkasan))
                                                                                    {{ $buku->ringkasan }}
                                                                                
                                                                                <!-- Jika tidak ada Ringkasan yang dihasilkan -->
                                                                                @else
                                                                                    Ringkasan otomatis oleh AI belum tersedia untuk buku ini.
                                                                                @endif
                                                                            </p>

                                                                            <div class="mt-2 pt-2 border-top border-primary border-opacity-10 d-flex align-items-start text-primary text-opacity-50 text-xs" style="font-size: 0.75rem; line-height: 1.4;">
                                                                                <i class="bi bi-exclamation-triangle-fill me-2 mt-0.5 flex-shrink-0"></i>
                                                                                <span>
                                                                                    <strong>Disclaimer:</strong> Ringkasan ini dibuat otomatis oleh AI dan bisa saja keliru. Harap diverifikasi kembali kebenarannya dari sumber resmi.
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Menampilkan Rating & Ulasan -->
                                            <div class="col-12 mt-4 border-top border-secondary border-opacity-25 pt-4">
                                                <!-- Menampilkan Jumlah orang  yang review buku -->
                                                <h6 class="mb-3 d-flex align-items-center gap-2" style="font-size: 1rem; font-weight: 600;">
                                                    <i class="bi bi-chat-square-text text-primary"></i> 
                                                    <span>Review & Ulasan ({{ $buku->review_count ?? 0 }})</span>
                                                </h6>

                                                <!-- Jika ada review pada buku -->
                                                <div class="row g-3"> 
                                                    @forelse($buku->review as $review)
                                                        <div class="col-12 col-lg-6">
                                                            <div class="p-3 h-100 border border-primary rounded-3" style="background-color: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); background-color: rgba(108, 117, 125, 0.1) !important;">

                                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">

                                                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                                                        <!-- Reviewer: Orang yang review buku -->
                                                                        
                                                                        <!-- Menampilkan avatar reviewer, Jika tidak ada avatar yang ditambahkan, Gunakan template avatar -->
                                                                        <img src="{{ $review->photoUrl ?? asset('assets/compiled/jpg/1.jpg') }}" class="rounded-circle" style="width: 25px; height: 25px; object-fit: cover;" alt="User">
                                                                        
                                                                        <!-- Menampilkan nama reviewer -->
                                                                        <span class="fw-semibold" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $review->nama }}">
                                                                            {{ $review->nama }}
                                                                        </span>

                                                                        <!-- Karena admin & super admin bisa menambahkan review meskipun tidak pernah meminjam buku tersebut, Maka hanya memunculkan badge Role saja -->
                                                                        @if($review->role == 'admin' || $review->role == 'superadmin')
                                                                            <span class="badge bg-primary text-white" style="font-size: 0.75rem;" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ ucfirst($review->role) }}">
                                                                                {{ ucfirst($review->role) }}
                                                                            </span>
                                                                        <!-- Role member hanya bisa review buku yang PERNAH DIPINJAM. jadi memunculkan badge Role dan Verified Reviewer yang artinya memang pernah meminjam buku tersebut -->
                                                                        @else
                                                                            <span class="badge bg-primary text-white" style="font-size: 0.75rem;" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ ucfirst($review->role) }}">Member</span>
                                                                            <span class="badge bg-success text-white" style="font-size: 0.75rem;" data-bs-toggle="tooltip" data-bs-placement="top" title="Review Terverifikasi. Badge ini memastikan bahwa user pernah melakukan peminjaman buku ini">Verified Reviewer</span>
                                                                        @endif
                                                                    </div>

                                                                    <!-- Menampilkan rata-rata rating -->
                                                                    <div class="text-warning-custom style-bintang-kecil" style="font-size: 0.8rem;">
                                                                        ⭐<span class="text-white fw-bold">{{ $review->pivot->rating }}</span>
                                                                    </div>
                                                                </div>

                                                                <!-- Menampilkan pesan review -->
                                                                <p class="mb-0 text-secondary small" style="text-align: justify; line-height: 1.5;">
                                                                    {{ $review->pivot->pesan ?? 'Tidak ada pesan yang diberikan' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    <!-- Jika tidak ada review -->
                                                    @empty
                                                        <div class="col-12 text-center py-4 text-muted justify-content-center d-flex">
                                                            <i class="bi bi-chat-left-dots text-secondary text-opacity-50 me-2"></i>
                                                            <span class="small" style="font-size: 0.85rem; letter-spacing: 0.3px;">
                                                                Belum ada review/ulasan untuk buku ini.
                                                            </span>
                                                        </div>
                                                    @endforelse

                                                    <!-- Untuk cek apakah admin sedang login pernah review buku tersebut atau tidak -->
                                                    @php
                                                        $reviewUser = Auth::user()->review()->where('review_bukus.idBuku', $buku->idBuku)->first();
                                                    @endphp

                                                    <div class="mt-4 pt-3 border-top border-secondary border-opacity-25">
                                                        <!-- Untuk cek apakah anggota pernah meminjam buku ini atau tidak -->
                                                        @if(in_array($buku->idBuku, $bukuPernahDipinjam))

                                                            <!-- Jika belum pernah review buku tersebut, maka muncul menu -->
                                                            @if(!$reviewUser)
                                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                                    <span class="text-secondary small">Belum kasih Review? Yuk bagikan reviewmu untuk buku ini!</span>

                                                                    <!-- Button untuk Trigger Menu Review -->
                                                                    <button class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFormReview-{{ $buku->idBuku }}" aria-expanded="false">
                                                                        <i class="bi bi-pencil-square" style="line-height: 1; font-size: 0.95rem;"></i> <span>Tulis Review</span>
                                                                    </button>
                                                                </div>

                                                                <!-- Menu Tambah Review -->
                                                                <div class="collapse" id="collapseFormReview-{{ $buku->idBuku }}">
                                                                    <div class="card card-body mt-3 p-3 text-white border-secondary" style="background-color: rgba(255, 255, 255, 0.02);">
                                                                        <!-- Form Create View / Tambah Review -->
                                                                        <form action="{{ route('review.store') }}" method="POST" onsubmit="tampilLoadingAnimation(this)">
                                                                            @csrf

                                                                            <!-- Input ID Buku secara Otomatis -->
                                                                            <input type="hidden" name="idBuku" value="{{ $buku->idBuku }}">

                                                                            <div class="row g-3">
                                                                                <!-- Input Rating Bintang menggunakan Rater.JS -->
                                                                                <div class="col-12 col-md-4 border-end-md border-secondary border-opacity-25 text-center text-md-start pt-2">
                                                                                    <label class="form-label text-secondary fw-bold mb-1 d-block">Rating Bintang</label>
                                                                                    
                                                                                    <div class="rater-stars-create mx-auto mx-md-0" 
                                                                                        data-id="{{ $buku->idBuku }}">
                                                                                    </div>
                                                                                    
                                                                                    <input type="hidden" name="rating" id="inputRatingCreate-{{ $buku->idBuku }}" value="">
                                                                                    
                                                                                    <small class="text-muted d-block mt-1">(Klik bintang untuk menilai)</small>
                                                                                </div>

                                                                                <div class="col-12 col-md-8">
                                                                                    <!-- Input Isi Review / Pesan -->
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label text-secondary fw-bold mb-1">Isi Ulasan / Komentar</label>
                                                                                        <textarea class="form-control border-secondary text-white small" id="ulasan" name="pesan" rows="3" placeholder="Tulis ulasan jujurmu..." style="background-color: #141821; font-size: 0.85rem;"></textarea>
                                                                                    </div>
                                                                                    <div class="text-end">
                                                                                        <!-- Button untuk Submit / Kirim Review dengan animasi loading -->
                                                                                        <button type="submit" class="btn btn-xs btn-success fw-bold px-3">
                                                                                            <span id="text-button">
                                                                                                <i class="bi bi-send me-1"></i> Kirim Review
                                                                                            </span>
                                                                                            <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                                                                            <span id="text-loading" class="d-none">Loading...</span>   
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            <!-- Jika sudah pernah review -->
                                                            @else
                                                                <div class="p-3 rounded-3 border border-success border-opacity-25 d-flex justify-content-between align-items-center flex-wrap gap-3" style="background-color: rgba(25, 135, 84, 0.04);">
                                                                    <!-- Menampilkan pesan bahwa sudah memberikan review dengan bintang yang diberikan sebelumnya -->
                                                                    <div>
                                                                        <span class="badge bg-success mb-1" style="font-size: 0.8rem;"><i class="bi bi-check-circle-fill me-1"></i> Sudah Direview</span>
                                                                        <p class="mb-0 text-secondary small">Kamu sudah memberikan review untuk buku ini dengan rating <strong class="text-warning">⭐ {{ $reviewUser->pivot->rating }}/5</strong></p>
                                                                    </div>
                                                                    
                                                                    <!-- Menu Button -->
                                                                    <div class="d-flex gap-2">
                                                                        <!-- Button Edit Review untuk Trigger Menu Edit -->
                                                                        <button class="btn btn-sm btn-outline-warning d-inline-flex align-items-center gap-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEditReview-{{ $buku->idBuku }}">
                                                                            <i class="bi bi-pencil-square" style="line-height: 1; font-size: 0.95rem;"></i> 
                                                                            <span style="line-height: 1;" class="mt-1">Edit</span>
                                                                        </button>

                                                                        <!-- Button Hapus Review -->
                                                                        <form action="{{ route('review.destroy', $reviewUser->pivot->idReview) }}" method="POST" onsubmit="displayAlert(event, this, '{{ 'Apakah anda ingin menghapus review untuk buku ' . $buku->judul . ' ?' }}', 'warning')">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1">
                                                                                <i class="bi bi-trash3" style="line-height: 1; font-size: 0.95rem;"></i> 
                                                                                <span style="line-height: 1;" class="mt-1">Hapus</span>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>

                                                                <!-- Menu Edit Review -->
                                                                <div class="collapse" id="collapseEditReview-{{ $buku->idBuku }}">
                                                                    <div class="card card-body mt-3 p-3 text-white border-secondary" style="background-color: rgba(255, 255, 255, 0.02);">
                                                                        <!-- Form Edit Review -->
                                                                        <form action="{{ route('review.update', $reviewUser->pivot->idReview) }}" method="POST" onsubmit="tampilLoadingAnimation(this)">
                                                                            @csrf
                                                                            @method('PUT')

                                                                            <div class="row g-3">
                                                                                <!-- Input Rating Bintang menggunakan Rater.JS -->
                                                                                <div class="col-12 col-md-4 border-end-md border-secondary border-opacity-25 text-center text-md-start pt-2">
                                                                                    <label class="form-label text-secondary fw-bold mb-1 d-block">Ubah Rating</label>
                                                                                    
                                                                                    <div class="rater-stars-update mx-auto mx-md-0" data-id="{{ $buku->idBuku }}" data-rating-lama="{{ $reviewUser->pivot->rating ?? 0.0 }}"></div>
                                                                                    
                                                                                    <input type="hidden" name="rating" id="inputRatingUpdate-{{ $buku->idBuku }}" value="{{ $reviewUser->pivot->rating ?? '' }}">
                                                                                    
                                                                                    <small class="text-muted d-block mt-1">(Klik bintang untuk mengubah nilai)</small>
                                                                                </div>

                                                                                <div class="col-12 col-md-8">
                                                                                    <!-- Input Isi Review / Pesan -->
                                                                                    <div class="mb-2">
                                                                                        <label for="edit-ulasan-{{ $buku->idBuku }}" class="form-label text-secondary fw-bold mb-1">Ubah Pesan</label>
                                                                                        <textarea class="form-control border-secondary text-white small" id="edit-ulasan-{{ $buku->idBuku }}" name="pesan" rows="3" style="background-color: #141821; font-size: 0.85rem;">{{ $reviewUser->pivot->pesan }}</textarea>
                                                                                    </div>
                                                                                    <div class="text-end">
                                                                                        <!-- Button Simpan Review dengan Animasi Loading -->
                                                                                        <button type="submit" class="btn btn-xs btn-warning fw-bold px-3 text-dark">
                                                                                            <span id="text-button">
                                                                                                <i class="bi bi-arrow-clockwise me-1"></i> Simpan Perubahan
                                                                                            </span>
                                                                                            <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                                                                            <span id="text-loading" class="d-none">Loading...</span>   
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Button Tutup Modal -->
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5">
                        <!-- Jika total buku kurang dari 24, hanya menampilkan contoh "Menampilkan 1 sampai 10 dari 10 hasil" -->
                        @if($bukus->total() < 24)
                            <div>
                                <nav class="d-flex align-items-center justify-content-between w-100 mt-4" style="width: 100% !important;">
                                    <div class="d-none d-sm-flex align-items-center justify-content-between w-100">
                    
                                    <div class="small text-muted mb-0">
                                        Menampilkan
                                    <span class="fw-semibold">{{ $bukus->firstItem() }}</span>
                                        sampai
                                    <span class="fw-semibold">{{ $bukus->lastItem() }}</span>
                                        dari
                                    <span class="fw-semibold">{{ $bukus->total() }}</span>
                                        hasil
                                </nav>
                            </div>
                        <!-- Jika total buku lebih dari 24, gunakan pagination bawaan laravel -->
                        @else
                            {{ $bukus->links('pagination::bootstrap-5') }}
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts') 
    <script src="{{ asset('assets/extensions/rater-js/index.js?v=2') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        // Mengubah select genre menggunakan Choices
        const genreSelect = document.getElementById('filter-genre');

        if(genreSelect){
            const choices = new Choices(genreSelect, {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Pilih kategori...',
                searchPlaceholderValue: 'Cari kategori...',
                shouldSort: false,
                itemSelectText: '',
            });
        }    

        // Untuk memanggil plugin RaterJS
        const collapse = document.querySelectorAll('.collapse');

        collapse.forEach(menu => {
            // Ketika menu edit / tambah review di buka
            menu.addEventListener('shown.bs.collapse', function () {                    
                const createContainer = this.querySelector('.rater-stars-create');
                
                // Ketika buka menu tambah review dan RaterJS belum dipanggil
                if(createContainer && !createContainer.classList.contains('rater-initialized')) {
                    const idBuku = createContainer.getAttribute('data-id');
                    const inputHiddenCreate = document.getElementById('inputRatingCreate-' + idBuku);

                    // Meanggil RaterJS
                    raterJs({
                        element: createContainer,
                        starSize: 24,
                        rating: 1,
                        rateCallback: function(rating, done) {
                            if (inputHiddenCreate) {
                                inputHiddenCreate.value = rating;
                            }
                            this.setRating(rating);
                            done();
                        }
                    });

                    createContainer.classList.add('rater-initialized');
                }

                // Ketika buka menu edit review dan RaterJS belum dipanggil
                const updateContainer = this.querySelector('.rater-stars-update');

                if(updateContainer && !updateContainer.classList.contains('rater-initialized')) {
                    const idBuku = updateContainer.getAttribute('data-id');
                    const ratingData = parseFloat(updateContainer.getAttribute('data-rating-lama')) || 0.0;
                    const inputHiddenUpdate = document.getElementById('inputRatingUpdate-' + idBuku);

                    // Memanggil RaterJS
                    raterJs({
                        element: updateContainer,
                        starSize: 24,
                        rating: ratingData,
                        rateCallback: function(rating, done) {
                            if (inputHiddenUpdate) {
                                inputHiddenUpdate.value = rating;
                            }
                            this.setRating(rating);
                            done();
                        }
                    });

                    updateContainer.classList.add('rater-initialized');
                }
            });
        });
    </script>
@endpush