@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bukuDisplay.css') }}">
    <style>
        /* Untuk menyembunyikan scroll bar pada tabel (Scroll dari kiri ke kanan) */
        .table-responsive {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <!-- Header: Judul Halaman & Button untuk Tambah Anggota -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Anggota</h4>

            <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i> Tambah Anggota
            </a>
        </div>

        <div class="card-body">
            <div class="card-content">
                <!-- Form untuk Cari Anggota menggunakan Email / Username / Nama & Filter menggunakan Role (Member/Admin/Superadmin) -->
                <form action="{{ route('admin.user.index') }}" method="GET" id="form-filter-user" onsubmit="tampilLoadingAnimation(this)">

                    <div class="row g-3">
                        <!-- Input Cari Anggota menggunakan Email / Username / Nama -->
                        <div class="col-md-5 col-12">
                            <label class="form-label fw-bold small mb-1">Cari anggota menggunakan Email / Nama / Username</label>

                            <input type="text" class="form-control border-secondary text-white search-input" id="search-user" name="search" value="{{ request('search') }}" placeholder="Ketik email atau nama atau username...">
                        </div>

                        <!-- Filter menggunakaan Role -->
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold small mb-1">Tampil anggota berdasarkan role</label>

                            <select class="form-select p-2" name="role" id="filter-role">
                                <option value="">Semua Role</option>
                                <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Member</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                            </select>
                        </div>

                        <!-- Menu Button -->
                        <div class="col-md-3 col-12">
                            <label class="form-label d-none d-md-block">&nbsp;</label> 

                            <div class="d-flex gap-2 w-100">
                                <!-- Button untuk Cari Anggota Berdasarkan Input Cari / Filter -->
                                <button type="submit" class="btn btn-success w-50 align-items-center gap-2">
                                    <span id="text-button">
                                        <i class="bi bi-search me-1"></i> Cari
                                    </span>
                                    <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                    <span id="text-loading" class="d-none">Loading...</span>
                                </button>

                                <!-- Jika ada input cari  atau Filter role dipilih, muncul button Reset untuk Bersihkan Input cari & Filter nya -->
                                @if(request('search') || request('role'))
                                    <a href="{{ route('admin.user.index') }}" class="btn btn-danger text-white w-50 align-items-center gap-2">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                    </a>
                                <!-- Jika tidak ada input cari atau Filter role tidak dipilih, Matikan button Reset -->
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

            <!-- Jika data user kosong -->
            @if($users->isEmpty())
                <div class="card-body text-center py-5">
                    <i class="bi bi-people-fill text-muted" style="font-size: 3rem;"></i>

                    <p class="mt-2 text-white">Belum ada anggota yang ditambahkan</p>

                    <!-- Munculkan button untuk Tambah Anggota jika data anggota kosong bukan berasal dari hasil filter atau cari -->
                    @if(!request('search') && !request('role'))
                        <a href="{{ route('admin.user.create') }}" class="btn btn-primary align-items-center gap-2 btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Anggota
                        </a>
                    @endif
                </div>
            <!-- Menampilkan data anggota dalam bentuk tabel -->
            @else
                <div class="table-responsive">
                    <table class="table table-lg align-middle">
                        <thead>
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 25%">USER PROFILE</th>
                                <th style="width: 5%">ROLE</th>
                                <th style="width: 15%">EMAIL</th>
                                <th style="width: 10%">NOMOR TELEPON</th>
                                <th style="width: 10%">JENIS KELAMIN</th>
                                <th style="width: 15%">ALAMAT</th>
                                <th style="width: 15%">AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($users as $index => $user)
                                <tr>
                                    <!-- Menampilkan Sistem Penomoran Baris -->
                                    <td class="text-bold-500">{{ $users->firstItem() + $index }}</td>

                                    <!-- Menampilkan Avatar, Username dan Nama Anggota -->
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <!-- Menampilkan Avatar -->
                                            <div class="avatar avatar-md bg-transparent rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 40px; height: 40px; overflow: hidden; border: none;">
                                                <a data-bs-toggle="modal" data-bs-target="#coverModal{{ $user->idUser }}">
                                                    <img src="{{ $user->photoUrl ?? asset('assets/compiled/jpg/1.jpg') }}" alt="Profile {{ $user->nama }}" style="width: 100%; height: 100%; object-fit: cover; display: block; border: none;">
                                                </a>
                                            </div>

                                            <!-- Menampilkan Nama & Username -->
                                            <div style="min-width: 0;">
                                                <h6 class="mb-0 fw-bold text-truncate" style="font-size: 0.95rem;">{{ $user->nama }}</h6>
                                                <small class="text-body-secondary d-block text-truncate">@​{{ $user->username }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Menampilkan Role Anggota menggunakan Badge
                                        Fungsi ucfirst : Untuk membuat huruf pertama menjadi kapital -->
                                    <td>
                                        @if($user->role == 'member')
                                            <span class="badge bg-success text-white fw-semibold px-2 py-1" style="font-size: 0.72rem;">{{ ucfirst($user->role) }}</span>
                                        @elseif($user->role == 'admin')
                                            <span class="badge bg-primary text-white fw-semibold px-2 py-1" style="font-size: 0.72rem;">{{ ucfirst($user->role) }}</span>
                                        @else
                                            <span class="badge bg-danger text-white fw-semibold px-2 py-1" style="font-size: 0.72rem;">{{ ucfirst($user->role) }}</span>
                                        @endif
                                    </td>

                                    <!-- Menampilkan email anggota -->
                                    <td class="text-bold-200">{{ $user->email }}</td>

                                    <!-- Menampilkan nomor telp anggota -->
                                    <td class="text-bold-200">{{ $user->nomorTelp }}</td>

                                    <!-- Menampilkan Jenis Kelamin Anggota menggunakan Badge -->
                                    <td>
                                        <span class="badge bg-success text-white fw-semibold px-2 py-1" style="font-size: 0.72rem;">{{ $user->jenisKelamin }}</span>
                                    </td>

                                    <!-- Menampikan alamat anggota -->
                                    <td class="text-bold-200">{{ $user->alamat }}</td>

                                    <!-- Menu Button -->
                                    <td>
                                        <div class="d-inline-flex align-items-center gap-3">
                                            <!-- Jika data anggota yang ditampilkan adalah anggota yang sedang login, maka hanya muncul button untuk edit anggota diri sendiri -->
                                            @if($user->idUser == auth()->id())
                                                <a href="{{ route('admin.user.edit', $user->idUser) }}" class="text-info p-0 line-height-1" data-bs-toggle="tooltip" title="Edit User">
                                                    <img src="{{ asset('assets/icons/edit.png') }}" alt="Edit Icon" style="width: 25px; height: 25px; object-fit: contain;">
                                                </a>
                                            <!-- Jika role anggota yang sedang login adalah Superadmin -->
                                            @elseif(auth()->user()->role == 'superadmin')
                                                <!-- Jika data anggota yang ditampilkan, rolenya adalah Admin -->
                                                @if($user->role == 'admin')                                         
                                                    <!-- Button untuk menurunkan Role Anggota menjadi Member -->          
                                                    <form method="POST" action="{{ route('admin.user.makeMember', $user->idUser) }}" class="m-0 p-0 d-inline-flex align-items-center" onsubmit="displayAlert(event, this, 'Apakah anda yakin ingin membuat user ini menjadi member?', 'warning')">
                                                        @csrf

                                                        <button class="border-0 bg-transparent p-0 text-success" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Turunkan role Admin menjadi Member">
                                                            <i class="fa-solid fa-user-minus d-inline-flex align-items-center"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Button untuk Edit Anggota -->
                                                    <a href="{{ route('admin.user.edit', $user->idUser) }}" class="text-info p-0 line-height-1" data-bs-toggle="tooltip" title="Edit User">
                                                        <img src="{{ asset('assets/icons/edit.png') }}" alt="Edit Icon" style="width: 25px; height: 25px; object-fit: contain;">
                                                    </a>

                                                    <!-- Button untuk Hapus Anggota -->
                                                    <form method="POST" action="{{ route('admin.user.destroy', $user->idUser) }}" onsubmit="displayAlert(event, this, 'Apakah anda yakin ingin menghapus user ini?', 'warning')">
                                                        @csrf

                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <button class="border-0 bg-transparent p-0 text-danger d-inline-flex align-items-center" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus User">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                            
                                                <!-- Jika data anggota yang ditampilkan, rolenya adalah Member -->
                                                @elseif($user->role == 'member')
                                                    <!-- Button untuk menaikkan Role Member menjadi Admin -->
                                                    <form method="POST" action="{{ route('admin.user.makeAdmin', $user->idUser) }}" class="m-0 p-0 d-inline-flex align-items-center" onsubmit="displayAlert(event, this, 'Apakah anda yakin ingin membuat user ini menjadi admin?', 'warning')">
                                                        @csrf

                                                        <button class="border-0 bg-transparent p-0 text-success d-inline-flex align-items-center" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Buat User Menjadi Admin">
                                                            <i class="fa-solid fa-crown"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Button untuk Edit Anggota -->
                                                    <a href="{{ route('admin.user.edit', $user->idUser) }}" class="text-info p-0 line-height-1" data-bs-toggle="tooltip" title="Edit User">
                                                        <img src="{{ asset('assets/icons/edit.png') }}" alt="Edit Icon" style="width: 25px; height: 25px; object-fit: contain;">
                                                    </a>

                                                    <!-- Button untuk Hapus Anggota -->
                                                    <form method="POST" action="{{ route('admin.user.destroy', $user->idUser) }}" class="m-0 p-0 d-inline-flex align-items-center" onsubmit="displayAlert(event, this, 'Apakah anda yakin ingin menghapus user ini?', 'warning')">
                                                        @csrf

                                                        <input name="_method" type="hidden" value="DELETE">

                                                        <button class="border-0 bg-transparent p-0 text-danger" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus User">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                            <!-- Jika anggota yang sedang login, rolenya adalah Admin -->
                                            @elseif(auth()->user()->role == 'admin')
                                                <!-- Jika data anggota, rolenya adalah bukan superadmin maupun admin -->
                                                @if($user->role != 'superadmin' || $user->role != 'admin')
                                                    <!-- Button untuk Edit Anggota -->
                                                    <a href="{{ route('admin.user.edit', $user->idUser) }}" class="text-info p-0 line-height-1" data-bs-toggle="tooltip" title="Edit User">
                                                        <img src="{{ asset('assets/icons/edit.png') }}" alt="Edit Icon" style="width: 25px; height: 25px; object-fit: contain;">
                                                    </a>

                                                    <!-- Button untuk Hapus Anggota -->
                                                    <form method="POST" action="{{ route('admin.user.destroy', $user->idUser) }}" class="m-0 p-0 d-inline-flex align-items-center" onsubmit="displayAlert(event, this, 'Apakah anda yakin ingin menghapus user ini?', 'warning')">
                                                        @csrf

                                                        <input name="_method" type="hidden" value="DELETE">

                                                        <button class="border-0 bg-transparent p-0 text-danger" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus User">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                        <!-- Simpelnya, Hanya Superadmin yang bisa menurunkan / menaikkan role. Sedangkan admin hanya bisa mengedit, menghapus dan menambahkan anggota yang role nya member -->
                                    </td>
                                </tr>

                                <!-- Trigger Modal ketika klik Avatar Anggota -->
                                <div class="modal fade" id="coverModal{{ $user->idUser }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content bg-dark border-secondary text-white">
                                            <div class="modal-header border-secondary">
                                                <h5 class="modal-title">{{ $user->nama }}'s Photo Profile</h5>

                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body text-center p-4">
                                                <img src="{{ $user->photoUrl ?? asset('assets/compiled/jpg/1.jpg') }}" alt="Photo Profile {{ $user->name }}" class="img-fluid rounded shadow-lg" style="max-height: 500px; object-fit: contain;">                    
                                            </div>

                                            <div class="modal-footer border-secondary">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center align-items-center mt-3">
                    <!-- Jika data anggota kurang dari 10, gunakan pagination buatan. Contoh: "Menampilkan 1 sampai 3 dari 3 hasil" -->
                    @if($users->total() <= 10)
                        <nav class="d-flex align-items-center justify-content-between w-100 mt-4" style="width: 100% !important;">
                            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 w-100">
                                <div class="small text-center text-sm-start text-muted mb-0">
                                    Menampilkan
                                    <span class="fw-semibold">{{ $users->firstItem() }}</span>
                                    sampai
                                    <span class="fw-semibold">{{ $users->lastItem() }}</span>
                                    dari
                                    <span class="fw-semibold">{{ $users->total() }}</span>
                                    hasil
                                </div>
                            </div>
                        </nav>
                    <!-- Jika data anggota lebih dari 10, gunakan pagination bawaan laravel -->
                    @else
                        {{ $users->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection