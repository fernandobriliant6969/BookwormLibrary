<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookworm Library</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/static/images/logo/favicon.ico?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    @stack('styles')
</head>

<body>
    <div id="app">
        <!-- Memanggil Sidebar -->
        @include('admin.layouts.sidebar')

        <div id="main">
            <!-- Header Aplikasi -->
            <div class="page-heading mb-2">
                <!-- Menampilkan Header / Top Navigation Bar khusus Mode Tablet / HP -->
                <div class="d-flex justify-content-between align-items-center d-xl-none mb-3">
                    <!-- Menu Burger untuk Membuka Sidebar -->
                    <a href="#" class="burger-btn d-inline-block">
                        <i class="bi bi-justify fs-3"></i>
                    </a>

                    <!-- Logo Aplikasi & Menu Dashboard -->
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('assets/compiled/png/logo.png') }}" class="rounded-circle" width="35" height="35" alt="Logo">
                    </a>

                    <!-- Avatar Anggota & Toggle Dropdown -->
                    <a data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->photoUrl ?? asset('assets/compiled/jpg/1.jpg') }}" class="rounded-circle" width="35" height="35" alt="Avatar">
                    </a>

                    <!-- Dropdown Toggle dari Avatar -->
                    <ul class="dropdown-menu dropdown-menu-start shadow-lg" aria-labelledby="topbarUserDropdown">
                        <li class="px-3 py-2 border-bottom border-light mb-2">
                            <div class="d-flex align-items-center">
                                <!-- Menampilkan Avatar Annggota -->
                                <div class="avatar avatar-md2 me-3">
                                    <img src="{{ Auth::user()->photoUrl ?? asset('assets/compiled/jpg/1.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                </div>
                                
                                <div class="text-start">
                                    <!-- Menampilkan Nama Anggota -->
                                    <h6 class="user-dropdown-name mb-0 text-white" style="font-size: 0.95rem; font-weight: 600;">
                                        {{ Auth::user()->nama }}
                                    </h6>
                                    <!-- Menampilkan Role Anggota -->
                                    <p class="user-dropdown-status text-muted mb-0" style="font-size: 0.75rem;">
                                        {{ ucfirst(Auth::user()->role) }}
                                    </p>
                                </div>
                            </div>
                        </li>

                        <!-- Menu Edit Profile -->
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person d-inline-flex align-items-center" ></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <!-- Menu Settings (Edit Password, Email & Hapus Akun) -->
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.security') }}">
                                <i class="bi bi-gear d-inline-flex align-items-center"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <!-- Menu Logout -->
                        <li>
                            <a class="dropdown-item text-danger d-flex align-items-center gap-2" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right d-inline-flex align-items-center"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>

                <div class="mt-4">
                    <!-- Untuk memberi tahu di halaman apa sekarang -->
                    <h3>@yield('current-page')</h3>
                </div>
            </div>

            <!-- Menampilkan Isi Content per Halaman -->
            @yield('content')

            <!-- Footer & Copyright -->
            <footer>
                <div class="footer clearfix mt-3 text-muted">
                    <div class="float-lg-start text-center">
                        <p>2026 &copy; Bookworm Library</p>
                    </div>
                    <div class="float-lg-end text-center">
                        <p>Created by <a href="https://github.com/fernandobriliant6969/BookwormLibrary">Kelompok 1</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/date-picker.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/loadingAnimation.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>   
    <!-- Menampilkan Script yang ditambahkan per Halaman (Jika ditambahkan) -->
    @stack('scripts')

    <!-- Jika ada session yang dikembalikan, maka tampilkan displayMessageAnimation menggunakan Sweetalert 2-->
    @if(session('success'))
        <script>
            displayMessageAnimation('success', 'Sukses', @json(session('success')));
        </script>
    @elseif(session('failed'))
        <script>
            displayMessageAnimation('failed', 'Gagal', @json(session('failed')));
        </script>
    @endif
</body>

</html>
