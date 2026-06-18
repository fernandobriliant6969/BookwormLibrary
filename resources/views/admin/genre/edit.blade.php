@extends('admin.layouts.main')

@section('current-page', 'Edit Genre: ' . $genre->nama)

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-content">
                <form class="form" method="POST" action="{{ route('admin.genre.update', $genre->idGenre) }}" onsubmit="tampilLoadingAnimation(this)">
                    @method('PUT')
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
                                    <input type="text" id="nama-genre" class="form-control mt-1" value="{{ old('nama') ?? $genre->nama }}" name="nama">
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
                                    <input type="text" id="deskripsi-genre" class="form-control mt-1" value="{{ old('deskripsi') ?? $genre->deskripsi }}" name="deskripsi">
                                    <div class="form-control-icon">
                                        <i class="bi bi-card-heading"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-flex flex-wrap justify-content-end gap-2 mt-3">
                            <a href="{{ route('admin.genre.index') }}" class="btn btn-warning text-white fw-semibold px-3 py-2">
                                Kembali ke List
                            </a>

                            <button type="submit" class="btn btn-primary fw-semibold">
                                <span id="text-button">Edit Genre</span>
                                <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                <span id="text-loading" class="d-none">Loading...</span>   
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection