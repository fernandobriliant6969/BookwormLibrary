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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    @stack('styles')
</head>

<body>
    <div id="app">

        @include('layouts.sidebar')

        <div id="main">
            <div class="page-heading mb-2">
                <div class="d-flex justify-content-between align-items-center mb-3 w-100 d-lg-none">
                    <a href="#" class="burger-btn d-inline-block">
                        <i class="bi bi-justify fs-3"></i>
                    </a>

                    <a href="{{ route('member.dashboard') }}">
                        <img src="{{ asset('assets/compiled/png/logo.png') }}" class="rounded-circle" width="35" height="35" alt="Logo">
                    </a>

                    <a data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->photoUrl ?? asset('assets/compiled/jpg/1.jpg') }}" class="rounded-circle" width="35" height="35" alt="Avatar">
                    </a>

                    <ul class="dropdown-menu dropdown-menu-start shadow-lg" aria-labelledby="topbarUserDropdown">
                        <li class="px-3 py-2 border-bottom border-light mb-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-md2 me-3">
                                    <img src="{{ Auth::user()->photoUrl ?? asset('assets/compiled/jpg/1.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                </div>
                                
                                <div class="text-start">
                                    <h6 class="user-dropdown-name mb-0 text-white" style="font-size: 0.95rem; font-weight: 600;">
                                        {{ Auth::user()->nama }}
                                    </h6>
                                    <p class="user-dropdown-status text-muted mb-0" style="font-size: 0.75rem;">
                                        {{ ucfirst(Auth::user()->role) }}
                                    </p>
                                </div>
                            </div>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person d-inline-flex align-items-center" ></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.security') }}">
                                <i class="bi bi-gear d-inline-flex align-items-center"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
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

                <h3 class="d-none d-lg-block">@yield('current-page')</h3>
            </div>
            @yield('content')

            <footer>
                <div class="footer clearfix mt-3 text-muted">
                    <div class="float-lg-start text-center">
                        <p>2026 &copy; Bookworm Library</p>
                    </div>
                    <div class="float-lg-end text-center">
                        <p>Created by <a href="https://github.com/Fefiria/2529250014-Kelompok1-Perpustakaan">Kelompok 1</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/loadingAnimation.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('scripts')

    @if(session('success'))
        <script>
            displayMessageAnimation('success', 'Sukses', @json(session('success')));
        </script>
    @endif
</body>

</html>
