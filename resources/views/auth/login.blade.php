@extends('layouts.auth')

@section('content')
    <div id="auth" class="min-vh-100">
        <div class="row g-0 min-vh-100">
            <div class="col-lg-5 col-12 d-flex align-items-center justify-content-center p-3 p-sm-4">
                
                <div class="card bg-white border-0 shadow-lg w-100 rounded-4 p-4 p-md-5" style="max-width: 480px;">
                    
                    <div class="card-header bg-transparent border-0 text-center p-0 mb-4">
                        <img src="{{ asset('assets/compiled/png/logo.png') }}" alt="Logo" class="mx-auto d-block" style="max-width: 110px; height: auto;">
                        <h2 class="fw-bold mt-3 fs-3">Bookworm Library</h2>
                    </div>

                    <div class="card-body p-0">
                        <form method="POST" action="{{ route('login') }}" onsubmit="tampilLoadingAnimation(this)">
                            @csrf

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade rounded-3 small mb-4" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                    {{ $errors->first() }}
                                    <button type="button" class="btn-close bg-transparent" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="mb-2">
                                <label class="form-label small text-secondary fw-semibold">Email</label>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <span><i class="bi bi-person"></i></span>
                                    </div>

                                    <input type="email" name="email" class="form-control form-control-md" value="{{ old('email') }}" required placeholder="Masukkan email">
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label small text-secondary fw-semibold">Password</label>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <span>
                                            <i class="bi bi-shield-lock"></i>
                                        </span>
                                    </div>
                                    
                                    <input type="password" id="password" name="password" class="form-control form-control-md" required placeholder="Masukkan password">
                                    
                                    <button class="btn border-secondary bg-white text-dark" type="button" id="btnTogglePassword">
                                        <i class="bi bi-eye" id="toggleIcon"></i> 
                                    </button>
                                </div>
                            </div>

                            <div class="form-check form-check-lg d-flex align-items-center mt-4 mb-4">
                                <input class="form-check-input me-2" type="checkbox" id="formRememberMe" name="remember" style="cursor: pointer;">
                                <label class="form-check-label small text-secondary" for="formRememberMe" style="cursor: pointer; user-select: none;">
                                    Ingatkan Saya
                                </label>
                            </div>

                            <button type="submit" class="btn text-white btn-block btn-lg shadow mt-2 rounded-3 d-flex align-items-center justify-content-center gap-2" style="background-color: #435ebe; height: 50px;">
                                <span id="text-button" class="fw-bold">Login</span>
                                <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                <span id="text-loading" class="d-none fw-bold">Loading...</span>     
                            </button>
                        </form>
                            
                        <div class="text-center mt-4 pt-2 small">
                            <p class="text-muted mb-1">Belum punya akun? <a href="{{ route('register') }}" class="fw-bold" style="color: #435ebe;">Daftar Disini</a></p>
                            <p class="mb-0"><a href="{{ route('password.request') }}" class="text-muted">Lupa password?</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right" class="h-100 w-100">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('btnTogglePassword');
        const toggleIcon = document.getElementById('toggleIcon');

        toggleButton.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        });
    </script>
@endpush