@extends('admin.layouts.main')

@section('current-page','Tambah Anggota')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-content">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible show fade pb-1" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{  $error }}</li>
                            @endforeach 
                        </ul> 
                        
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form class="form" method="POST" action="{{ route('admin.user.store') }}" enctype="multipart/form-data" onsubmit="tampilLoadingAnimation(this)">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="nama-column">Nama Anggota</label>

                                <div class="position-relative">
                                    <input type="text" id="nama-column" class="form-control mt-1" placeholder="Masukkan nama anggota..." name="nama" value="{{ old('nama') }}">

                                    <div class="form-control-icon">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="username-column">Username</label>

                                <div class="position-relative">
                                    <input type="text" id="username-column" class="form-control mt-1" placeholder="Masukkan username..." name="username" value="{{ old('username') }}">

                                    <div class="form-control-icon">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                                
                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="email-column">Email</label>

                                <div class="position-relative">
                                    <input type="email" id="email-column" class="form-control mt-1" placeholder="Masukkan email..." name="email" value="{{ old('email') }}">

                                    <div class="form-control-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="nomorTelp-column">Nomor Telp</label>

                                <div class="position-relative">
                                    <input type="text" id="nomorTelp-column" class="form-control mt-1" placeholder="Masukkan nomor telp..." name="nomorTelp" value="{{ old('nomorTelp') }}">

                                    <div class="form-control-icon">
                                        <i class="bi bi-phone"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="jenis-kelamin-column">Jenis Kelamin</label>

                                <div class="input-group">
                                    <span class="input-group-text pt-0">
                                        <i class="bi bi-gender-ambiguous"></i>
                                    </span>

                                    <select name="jenisKelamin" class="form-select form-control-xl border-start-0" style="border-top-right-radius: 0.7rem; border-bottom-right-radius: 0.7rem; padding-left: 0.5rem; font-size: 1.1rem; height: 100%;">
                                        <option value="" disabled {{ old('jenisKelamin') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('jenisKelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenisKelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="alamat-column">Alamat</label>

                                <div class="position-relative">
                                    <input type="text" id="alamat-column" class="form-control mt-1" placeholder="Masukkan alamat..." name="alamat" value="{{ old('alamat') }}">

                                    <div class="form-control-icon">
                                        <i class="bi bi-building"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="password-column">Password</label>

                                <div class="position-relative">
                                    <input type="password" id="password-column" class="form-control mt-1" placeholder="Masukkan password..." name="password" value="{{ old('password') }}">

                                    <div class="form-control-icon">
                                        <i class="bi bi-shield-lock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                                
                        <div class="col-12 d-flex flex-wrap justify-content-end gap-2 mt-3">
                            <a href="{{ route('admin.user.index') }}" class="btn btn-warning text-white align-items-center fw-semibold px-3 py-2">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke List
                            </a>
                                    
                            <button type="submit" class="btn btn-primary fw-semibold align-items-center px-3 py-2">
                                <span id="text-button">
                                    <i class="bi bi-pencil-square me-1"></i>Tambah Anggota
                                </span>
                                <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                <span id="text-loading" class="d-none">Loading...</span>   
                            </button>

                            <button type="reset" class="btn btn-danger text-white align-items-center fw-semibold px-3 py-2">
                                <i class="bi bi-arrow-repeat me-1"></i>Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection