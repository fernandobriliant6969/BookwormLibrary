<div id="sidebar">
    <div class="sidebar-wrapper active d-flex flex-column">
        <div class="sidebar-header position-relative px-2 pt-4 pb-0">
            <div class="d-flex align-items-center justify-content-center w-100 position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="logo">
                        <a href="{{ route('admin.dashboard') }}">
                            <img src="{{ asset('assets/compiled/png/logo.png') }}" alt="Logo" style="width: 45px; height: 45px;">
                        </a>
                    </div>

                    <div class="apptitle">
                        <p class="text-dark-theme-white fs-5 mb-0 fw-bold text-nowrap">Bookworm Library</p>
                    </div>
                </div>

                <div class="sidebar-toggler x position-absolute" style="right: 10px;">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu flex-grow-1">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Route::is('admin.buku.listbuku') ? 'active' : '' }}">
                    <a href="{{ route('admin.buku.listbuku') }}" class='sidebar-link'>
                        <i class="bi bi-journal-text"></i>
                        <span>List Buku</span>
                    </a>
                </li>

                <li class="sidebar-item has-sub {{ Route::is('admin.buku.*') && !Route::is('admin.buku.listbuku') ? 'active' : '' }}">
                    <a class='sidebar-link'>
                        <i class="bi bi-journal-bookmark"></i>
                        <span>Buku</span>
                    </a>

                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="{{ route('admin.buku.create') }}" class="submenu-link">Tambah Buku</a>
                        </li>

                        <li class="submenu-item">
                            <a href="{{ route('admin.buku.index') }}" class="submenu-link">Manage Buku</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item has-sub {{ Route::is('admin.genre.*') ? 'active' : '' }}">
                    <a class='sidebar-link'>
                        <i class="bi bi-box-seam"></i>
                        <span>Genre</span>
                    </a>

                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="{{ route('admin.genre.create') }}" class="submenu-link">Tambah Genre</a>
                        </li>

                        <li class="submenu-item">
                            <a href="{{ route('admin.genre.index') }}" class="submenu-link">Manage Genre</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item has-sub {{ Route::is('admin.peminjaman.*') ? 'active' : '' }}">
                    <a class='sidebar-link'>
                        <i class="bi bi-bag"></i>
                        <span>Peminjaman</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="{{ route('admin.peminjaman.create') }}" class="submenu-link">Tambah Peminjaman</a>
                        </li>

                        <li class="submenu-item">
                            <a href="{{ route('admin.peminjaman.index') }}" class="submenu-link">Manage Peminjaman</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item has-sub {{ Route::is('admin.user.*') ? 'active' : '' }}">
                    <a class='sidebar-link'>
                        <i class="bi bi-people-fill"></i>
                        <span>User</span>
                    </a>

                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="{{ route('admin.user.create') }}" class="submenu-link">Tambah User</a>
                        </li>

                        <li class="submenu-item">
                            <a href="{{ route('admin.user.index') }}" class="submenu-link">Manage User</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer p-4 border-top mt-auto w-100">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="dropdown">
                    <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar avatar-md2 me-2">
                            <img src="{{ Auth::user()->photoUrl ?? asset('assets/compiled/jpg/1.jpg') }}" alt="Avatar" class="rounded-circle">
                        </div>

                        <div class="text text-start">
                            <h6 class="user-dropdown-name mb-0 text-dark-theme-white" style="font-size: 0.95rem; font-weight: 600;">
                                {{ Auth::user()->nama }}
                            </h6>

                            <p class="user-dropdown-status text-muted mb-0" style="font-size: 0.75rem;">
                                {{ ucfirst(Auth::user()->role) }}
                            </p>
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-start shadow-lg" aria-labelledby="topbarUserDropdown">
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

                <div class="theme-toggle d-flex gap-1 align-items-center ms-2">
                    <i class="bi bi-sun-fill text-warning position-relative" style="font-size: 13px; top: -1px;"></i>

                    <div class="form-check form-switch mb-0 pe-0">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer; transform: scale(0.85);">
                    </div>

                    <i class="bi bi-moon-fill text-white position-relative" style="font-size: 12px; top: -1px;"></i>
                </div>
            </div>
        </div>
    </div>
</div>
