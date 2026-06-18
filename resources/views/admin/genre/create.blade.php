@extends('admin.layouts.main')

@section('current-page', 'Tambah Genre')

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form class="form" method="POST" action="{{ route('admin.genre.store') }}" onsubmit="tampilLoadingAnimation(this)">
                        @csrf

                        <div class="row">
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

                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="nama-genre">Nama Genre</label>
                                    <div class="position-relative">
                                        <input type="text" id="nama-genre" class="form-control mt-1" placeholder="Masukkan nama genre..." value="{{ old('nama') }}" name="nama">
                                        <div class="form-control-icon">
                                            <i class="bi bi-pencil-square"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="deskripsi-genre">Deskripsi Genre</label>
                                    <div class="position-relative">
                                        <input type="text" id="deskripsi-genre" class="form-control mt-1" placeholder="Masukkan deskripsi genre..." value="{{ old('deskripsi') }}" name="deskripsi">
                                        <div class="form-control-icon">
                                            <i class="bi bi-card-heading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 d-flex flex-wrap justify-content-end gap-2 mt-3">
                                <a href="{{ route('admin.genre.index') }}" class="btn btn-warning text-white align-items-center fw-semibold px-3 py-2">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali ke List
                                </a>
                                
                                <button type="submit" class="btn btn-primary align-items-center fw-semibold px-3 py-2">
                                    <span id="text-button">
                                        <i class="bi bi-pencil-square me-1"></i>Tambah Genre
                                    </span>
                                    <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                    <span id="text-loading" class="d-none">Loading...</span>   
                                </button>
                            
                                <button type="reset" class="btn btn-danger text-white align-items-center px-3 py-2">
                                    <i class="bi bi-arrow-repeat me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection