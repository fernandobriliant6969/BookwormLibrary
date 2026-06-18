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
                        <form method="POST" action="{{ route('password.email') }}" onsubmit="tampilLoadingAnimation(this)">
                            @csrf

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade rounded-3 small mb-4" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                    {{ $errors->first() }}
                                    <button type="button" class="btn-close bg-transparent" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('status'))
                                <div class="alert alert-success alert-dismissible show fade rounded-3 small mb-4" role="alert">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ session('status') }}
                                    <button type="button" class="btn-close bg-transparent" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="mb-4">
                                <label class="form-label small text-secondary fw-semibold">Email</label>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <span><i class="bi bi-envelope"></i></span>
                                    </div>

                                    <input type="email" name="email" class="form-control form-control-lg rounded-3 fs-6" value="{{ old('email') }}" required autofocus placeholder="Masukkan email">
                                </div>
                            </div>

                            <button type="submit" class="btn text-white btn-block btn-lg shadow mt-4" style="background-color: #435ebe; height: 50px;">
                                <span id="text-button" class="fw-bold">Reset Password</span>
                                <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                <span id="text-loading" class="d-none fw-bold">Loading...</span>     
                            </button>

                            <div class="text-center mt-4 pt-2 small">
                                <p class="text-muted mb-1"><a href="{{ route('login') }}" class="fw-bold" style="color: #435ebe;">Kembali ke Halaman Login</a></p>
                            </div>
                        </form>
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