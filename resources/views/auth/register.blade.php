@extends('layouts.auth')

@section('content')
    <div id="auth">
        <div class="row g-0">
            <div class="col-lg-5 col-12 d-flex align-items-center justify-content-center p-3 p-sm-4">

                <div class="card bg-white border-0 shadow-lg w-100 rounded-4 p-4 p-md-5" style="max-width: 480px;">

                    <!-- Logo Aplikasi -->
                    <div class="card-header bg-transparent border-0 text-center p-0 mb-4">
                        <img src="{{ asset('assets/compiled/png/logo.png') }}" alt="Logo" class="mx-auto d-block" style="max-width: 110px; height: auto;">
                        <h2 class="fw-bold mt-3 fs-3">Bookworm Library</h2>
                    </div>

                    <div class="card-body p-0">
                        <!-- Form Register -->
                        <form method="POST" action="{{ route('register') }}" onsubmit="tampilLoadingAnimation(this)">
                            @csrf

                            <!-- Untuk menampilkan alert error jika email dan password yang dimasukkan salah -->
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade pb-1" role="alert">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{  $error }}</li>
                                        @endforeach 
                                    </ul> 
                                    <button type="button" class="btn-close bg-transparent" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Input Nama -->
                            <div class="mb-2">
                                <label class="form-label small text-secondary fw-semibold">Nama</label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <span><i class="bi bi-person-circle"></i></span>
                                    </div>

                                    <input type="text" name="nama" class="form-control form-control-md" value="{{ old('nama') }}" placeholder="Masukkan nama">
                                </div>
                            </div>

                            <!-- Input Username -->
                            <div class="mb-2">
                                <label class="form-label small text-secondary fw-semibold">Username</label>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <span><i class="bi bi-person"></i></span>
                                    </div>

                                    <input type="text" name="username" class="form-control form-control-md" value="{{ old('username') }}" placeholder="Masukkan username">
                                </div>
                            </div>

                            <!-- Input Email -->
                            <div class="mb-2">
                                <label class="form-label small text-secondary fw-semibold">Email</label>
                                
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <span><i class="bi bi-envelope"></i></span>
                                    </div>

                                    <input type="email" name="email" class="form-control form-control-md" value="{{ old('email') }}" placeholder="Masukkan email">
                                </div>
                            </div>

                            <!-- Input Nomor Telp -->
                            <div class="mb-2">
                                <label class="form-label small text-secondary fw-semibold">Nomor Telp</label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <span><i class="bi bi-phone"></i></span>
                                    </div>

                                    <input type="text" name="nomorTelp" class="form-control form-control-md" value="{{ old('nomorTelp') }}" placeholder="Masukkan nomor telepon">
                                </div>
                            </div>

                            <!-- Pilih Jenis Kelamin -->
                            <div class=" mb-2">
                                <label class="form-label small text-secondary fw-semibold">Jenis Kelamin</label>

                                <div class="input-group"> 
                                    <div class="input-group-text">
                                        <span><i class="bi bi-gender-ambiguous"></i></span>
                                    </div>
                                    
                                    <select name="jenisKelamin" class="form-select form-control-md">
                                        <option value="" disabled {{ old('jenisKelamin') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('jenisKelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenisKelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Input Alamat -->
                            <div class="mb-2">
                                <label class="form-label small text-secondary fw-semibold">Alamat</label>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <span><i class="bi bi-building"></i></span>
                                    </div>

                                    <input type="text" name="alamat" class="form-control form-control-md" value="{{ old('alamat') }}" placeholder="Masukkan alamat">
                                </div>
                                
                            </div>
                            
                            <!-- Input Password -->
                            <div class="mb-2">
                                <label class="form-label small text-secondary fw-semibold">Password</label>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <span>
                                            <i class="bi bi-shield-lock"></i>
                                        </span>
                                    </div>
                                    
                                    <input type="password" id="password" name="password" class="form-control form-control-md fs-6" required autocomplete="current-password" placeholder="Masukkan password">
                                    
                                    <button class="btn border-secondary bg-white text-dark" type="button" id="btnTogglePassword">
                                        <i class="bi bi-eye" id="toggleIconPassword"></i> 
                                    </button>
                                </div>
                            </div>

                            <!-- Input Konfirmasi Password -->
                            <div class="mb-2">
                                <label class="form-label small text-secondary fw-semibold">Password Konfirmasi</label>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <span>
                                            <i class="bi bi-shield-lock"></i>
                                        </span>
                                    </div>
                                    
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-md fs-6" required placeholder="Masukkan konfirmasi password">
                                    
                                    <button class="btn border-secondary bg-white text-dark" type="button" id="btnTogglePasswordConfirmation">
                                        <i class="bi bi-eye" id="toggleIconPasswordConfirmation"></i> 
                                    </button>
                                </div>
                            </div>

                            <!-- Button untuk register dengan memunculkan animasi loading ketika form register di submit -->
                            <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-2" type="submit">
                                <span id="text-button">Daftar</span>
                                <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                <span id="text-loading" class="d-none">Loading...</span> 
                            </button>
                        </form>
                    </div>
                    
                    <!-- Petunjuk / bantuan untuk sudah punya akun -->
                    <div class="text-center mt-4 pt-2 small">
                        <p class="text-muted mb-1">Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold" style="color: #435ebe;">Login Disini</a></p>
                    </div>
                </div>
            </div>

            <!-- Untuk background perpustakaan yang muncul di Laptop / PC, Sedangkan di HP hanya muncul form login saja-->
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Script untuk button toggle / lihat password pada input password -->
    <script>
        const passwordInput = document.getElementById('password');
        const toggleButtonPassword = document.getElementById('btnTogglePassword');
        const toggleIconPassword = document.getElementById('toggleIconPassword');

        toggleButtonPassword.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIconPassword.classList.remove('bi-eye');
                toggleIconPassword.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIconPassword.classList.remove('bi-eye-slash');
                toggleIconPassword.classList.add('bi-eye');
            }
        });
        
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const toggleButtonPasswordConfirmation = document.getElementById('btnTogglePasswordConfirmation');
        const toggleIconPasswordConfirmation = document.getElementById('toggleIconPasswordConfirmation');

        toggleButtonPasswordConfirmation.addEventListener('click', function () {
            if (passwordConfirmationInput.type === 'password') {
                passwordConfirmationInput.type = 'text';
                toggleIconPasswordConfirmation.classList.remove('bi-eye');
                toggleIconPasswordConfirmation.classList.add('bi-eye-slash');
            } else {
                passwordConfirmationInput.type = 'password';
                toggleIconPasswordConfirmation.classList.remove('bi-eye-slash');
                toggleIconPasswordConfirmation.classList.add('bi-eye');
            }
        });

    </script>
@endpush
