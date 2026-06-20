@extends('admin.layouts.main')

@section('content')
    <div class="card">
        <!-- Header: Judul Halaman & Button untuk Tambah Peminjaman -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Peminjaman</h4>
            <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i> Tambah Peminjaman
            </a>
        </div>

        <div class="card-body">
            <div class="card-content">
                <!-- Form untuk Cari Peminjaman menggunakan Nama Anggota / Judul Buku & Filter Peminjaman menggunakan Status Peminjaman -->
                <form action="{{ route('admin.peminjaman.index') }}" method="GET" id="form-filter-peminjaman" onsubmit="tampilLoadingAnimation(this)">
                    <div class="row g-3 align-items-end">
                        <!-- Input Cari Peminjaman menggunakan Nama Anggota / Judul Buku -->
                        <div class="col-md-5 col-12">
                            <label class="form-label fw-bold small mb-1">Cari Peminjaman menggunakan Nama Anggota / Judul Buku</label>

                            <input type="text" class="form-control border-secondary text-white custom-search-input" id="search-peminjaman" name="search" value="{{ request('search') }}" placeholder="Ketik nama member atau judul buku...">
                        </div>

                        <!-- Select Status Peminjaman -->
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold small mb-1">Tampil Peminjaman berdasarkan Status Peminjaman</label>

                            <select class="form-select p-2" name="status" id="filter-status">
                                <option value="">Semua Status</option>
                                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Dikembalikan Sebagian" {{ request('status') == 'Dikembalikan Sebagian' ? 'selected' : '' }}>Dikembalikan Sebagian</option>
                                <option value="Telah Dikembalikan" {{ request('status') == 'Telah Dikembalikan' ? 'selected' : '' }}>Telah Dikembalikan</option>
                                <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                            </select>
                        </div>

                        <!-- Menu Button -->
                        <div class="col-md-3 col-12">
                            <label class="form-label d-none d-md-block">&nbsp;</label> 

                            <div class="d-flex gap-2 w-100">
                                <!-- Button untuk Cari berdasarkan Input Cari & Filter -->
                                <button type="submit" class="btn btn-success w-50 align-items-center gap-2">
                                    <span id="text-button">
                                        <i class="bi bi-search me-1"></i> Cari
                                    </span>
                                    <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                    <span id="text-loading" class="d-none">Loading...</span>
                                </button>

                                <!-- Jika Input Cari ada isi atau Filter Status dipilih, Maka muncul button untuk Reset / Bersihkan Filter nya -->
                                @if(request('search') || request('status'))
                                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-danger text-white w-50 align-items-center gap-2">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                    </a>
                                <!-- Jika tidak mengisi input cari / filter maka matikan button Reset nya -->
                                @else
                                    <button type="button" class="btn btn-secondary w-50 align-items-center gap-2 disabled" style="opacity: 0.5;">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Jika data peminjaman masih kosong -->
                @if($peminjamans->isEmpty())
                    <div class="text-center">
                        <i class="bi bi-journal-bookmark text-muted" style="font-size: 3rem;"></i>

                        <p class="mt-2 text-white">Belum ada peminjaman yang ditambahkan</p>
                        
                        <!-- Jika data kosong bukan dari hasil cari atau filter, Muncul button untuk Tambah Peminjaman -->
                        @if(!request('search') && !request('status'))
                            <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-primary align-items-center gap-2 btn-sm">
                                <i class="bi bi-pencil-square me-1"></i> Tambah Peminjaman
                            </a>    
                        @endif
                    </div>
                <!-- Menampilkan data peminjaman dalam tabel -->
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="table-peminjaman">
                            <thead class="border-bottom border-secondary-subtle">
                                <tr>
                                    <th style="width: 5%" class="py-3 text-center">NO</th>
                                    <th style="width: 25%" class="py-3">PEMINJAM</th>
                                    <th style="width: 35%" class="py-3">BUKU YANG DIPINJAM</th>
                                    <th style="width: 15%" class="py-3">PERIODE PINJAM</th>
                                    <th style="width: 10%" class="py-3 text-center">STATUS</th>
                                    <th style="width: 10%" class="py-3 text-center">AKSI</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($peminjamans as $index => $pinjam)
                                    <tr class="border-bottom border-light-subtle">
                                        <!-- Menampilkan Sistem Penomoran Baris -->
                                        <td class="text-center text-bold-500">{{ $peminjamans->firstItem() + $index }}</td>   

                                        <!-- Menampilkan Avatar, Nama & Username Peminjam -->
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar avatar-md bg-transparent rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 40px; height: 40px; overflow: hidden; border: none;">
                                                    <img src="{{ $pinjam->user->photoUrl ?? asset('assets/compiled/jpg/1.jpg') }}" alt="Profile {{ $pinjam->user->nama }}" style="width: 100%; height: 100%; object-fit: cover; display: block; border: none;">
                                                </div>

                                                <div style="min-width: 0;">
                                                    <h6 class="mb-0 fw-bold text-truncate" style="font-size: 0.95rem;">{{ $pinjam->user->nama }}</h6>
                                                    <small class="text-body-secondary d-block text-truncate">@​{{ $pinjam->user->username }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Menampilkan semua buku yang dipinjam -->
                                        <td>
                                            <ul class="list-group list-group-flush mb-0 bg-transparent">
                                                @foreach($pinjam->details as $detail)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center p-1 bg-transparent border-0">

                                                    <!-- Menampilkan judul buku -->
                                                    <div class="text-truncate me-3 fw-medium" style="font-size: 0.88rem;" title="{{ $detail->buku->judul }}">
                                                        <i class="bi bi-book me-1 text-primary-emphasis small"></i> {{ $detail->buku->judul }}
                                                    </div>

                                                    <!-- Menampilkan status buku (Dipinjam / Dikembalikan) -->
                                                    @if($detail->status == 'dipinjam')
                                                        <span class="badge bg-primary text-white fw-semibold px-2 py-1" style="font-size: 0.72rem;">Dipinjam</span>
                                                    @else
                                                        <span class="badge bg-success text-white fw-semibold px-2 py-1" style="font-size: 0.72rem;">Dikembalikan</span>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                        </td>

                                        <!-- Menampilkan Periode Peminjaman (Tanggal Pinjam, Tanggal Kembali & Lama Pinjam) -->
                                        <td>
                                            <div style="font-size: 0.85rem;" class="lh-sm">
                                                <span class="d-block fw-semibold text-success-emphasis"><i class="bi bi-calendar-plus me-1"></i> {{ \Carbon\Carbon::parse($pinjam->tanggalPeminjaman)->format('d M Y') }}</span>
                                                <span class="d-block mt-1 fw-semibold text-danger-emphasis"><i class="bi bi-calendar-minus me-1"></i> {{ \Carbon\Carbon::parse($pinjam->tanggalKembali)->format('d M Y') }}</span>
                                                <span class="badge bg-secondary-subtle text-secondary-emphasis mt-2 fw-medium px-2 py-1">{{ $pinjam->lamaPinjam }} Hari</span>
                                            </div>
                                        </td>

                                        <!-- Menampilkan Status Peminjaman (Aktif/Dikembalikan Sebagian/Telah Dikembalikan/Terlambat) -->
                                        <td class="text-center">
                                            @if($pinjam->status == 'Aktif')
                                                <span class="badge bg-primary text-white fw-bold px-3 py-2 rounded-pill 6shadow-sm" style="font-size: 0.75rem;">Aktif</span>
                                            @elseif($pinjam->status == 'Dikembalikan Sebagian')
                                                <span class="badge bg-warning text-white fw-bold px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.75rem;">Dikembalikan Sebagian</span>
                                            @elseif($pinjam->status == 'Telah Dikembalikan')
                                                <span class="badge bg-success text-white fw-bold px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.75rem;">Telah Dikembalikan</span>
                                            @else
                                                <span class="badge bg-danger text-white fw-bold px-3 py-2 rounded-pill 6shadow-sm" style="font-size: 0.75rem;">Terlambat</span>
                                            @endif
                                        </td>

                                        <!-- Menu Button -->
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- Button untuk Edit Peminjaman -->
                                                <a href="{{ route('admin.peminjaman.edit', $pinjam->idPeminjaman) }}" class="btn btn-sm text-success" data-bs-toggle="tooltip" title="Kelola Peminjaman">
                                                    <i class="bi bi-gear-fill lh-1"></i>
                                                </a>

                                                <!-- Button untuk Hapus Peminjaman -->
                                                <form action="{{ route('admin.peminjaman.destroy', $pinjam->idPeminjaman) }}" method="POST" class="d-inline" onsubmit="displayAlert(event, this, 'Apakah anda ingin menghapus peminjaman ini?', 'warning')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-sm text-danger" data-bs-toggle="tooltip" title="Hapus Peminjaman">
                                                        <i class="bi bi-trash3-fill lh-1"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mt-3">
                        <!-- Jika data peminjaman kurang dari 10, maka gunakan paginate buatan. Contoh: "Menampilkan 1 sampai 8 dari 8 hasil" -->
                        @if($peminjamans->total() <= 10)
                            <nav class="d-flex align-items-center justify-content-between w-100 mt-4" style="width: 100% !important;">
                                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 w-100">
                                    <div class="small text-center text-sm-start text-muted mb-0">
                                        Menampilkan
                                        <span class="fw-semibold">{{ $peminjamans->firstItem() }}</span>
                                        sampai
                                        <span class="fw-semibold">{{ $peminjamans->lastItem() }}</span>
                                        dari
                                        <span class="fw-semibold">{{ $peminjamans->total() }}</span>
                                        hasil
                                    </div>
                                </div>
                            </nav>
                        <!-- Jika data peminjaman lebih dari 10, gunakan paginate bawaan laravel -->
                        @else
                            {{ $peminjamans->links('pagination::bootstrap-5') }}
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection