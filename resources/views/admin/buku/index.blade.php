@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bukuDisplay.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Buku</h4>
            <a href="{{ route('admin.buku.create') }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i> Tambah Buku
            </a>
        </div>

        <div class="card-body">
            <div class="card-content">
                <form action="{{ route('admin.buku.index') }}" method="GET" id="form-filter-buku" onsubmit="tampilLoadingAnimation(this)">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5 col-12">
                            <label class="form-label text-white fw-bold small mb-1">Cari Buku menggunakan Judul/Author</label>

                            <div class="input-group">
                                <span class="input-group-text border-secondary text-muted search-addon d-flex align-items-center justify-content-center">
                                    <i class="bi bi-search"></i>
                                </span>
                                
                                <input type="text" class="form-control border-secondary text-white custom-search-input" id="search-buku" name="search" value="{{ request('search') }}" placeholder="Ketik judul buku atau nama pengarang...">
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <label class="form-label text-white fw-bold small mb-1">Tampil buku berdasarkan kategori</label>

                            <select class="choices form-select" name="idGenre[]" id="filter-genre" multiple="multiple">
                                @foreach($genres as $g)
                                    <option value="{{ $g->idGenre }}" {{ is_array(request('idGenre')) && in_array($g->idGenre, request('idGenre')) ? 'selected' : '' }}>
                                        {{ $g->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-12">
                            <label class="form-label d-none d-md-block">&nbsp;</label> 

                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-success w-50 align-items-center gap-2">
                                    <span id="text-button">
                                        <i class="bi bi-search me-1"></i> Cari
                                    </span>
                                    <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                    <span id="text-loading" class="d-none">Loading...</span>
                                </button>

                                @if(request('search') || request('idGenre'))
                                    <a href="{{ route('admin.buku.index') }}" class="btn btn-danger text-white w-50 align-items-center gap-2">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                    </a>
                                @else
                                    <button type="button" class="btn btn-secondary w-50 align-items-center gap-2 disabled" style="opacity: 0.5;">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>

                @if($bukus->isEmpty())
                    <div class="text-center">
                        <i class="bi bi-journal-bookmark text-muted" style="font-size: 3rem;"></i>

                        <p class="mt-2 text-white">Belum ada buku yang ditambahkan</p>
                        
                        @if(!request('search') && !request('genreIds'))
                            <a href="{{ route('admin.buku.create') }}" class="btn btn-primary align-items-center gap-2 btn-sm">
                                <i class="bi bi-pencil-square"></i> Tambah Buku
                            </a>    
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 5%">NO</th>
                                    <th style="width: 15%">COVER</th>
                                    <th style="width: 20%">JUDUL</th>
                                    <th style="width: 10%">AUTHOR</th>
                                    <th style="width: 20%">GENRE</th>
                                    <th style="width: 10%">STATUS</th>
                                    <th style="width: 15%">STOK</th>
                                    <th style="width: 15%">AKSI</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($bukus as $index => $buku)
                                    <tr>
                                        <td class="text-bold-500">{{ $bukus->firstItem() + $index }}</td>

                                        <td>
                                            @if($buku->photoUrl)
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#coverModal{{ $buku->idBuku }}">
                                                    <img src="{{ $buku->photoUrl }}" alt="Cover {{ $buku->judul }}" class="rounded shadow-sm" style="width: 60px; height: 80px; object-fit: cover; object-position: center;" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $buku->judul }}">
                                                </a>
                                            @else
                                                <img src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=200&auto=format&fit=crop&q=60" alt="Default Cover" class="rounded shadow-sm" style="width: 60px; height: 80px; object-fit: cover; object-position: center;">
                                            @endif
                                        </td>

                                        <td class="text-bold-200 text-white">{{ $buku->judul }}</td>

                                        <td class="text-bold-200">{{ $buku->pengarang }}</td>

                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($buku->genre as $genre)
                                                    <span class="badge bg-primary text-white text-xs mb-1">
                                                        {{ $genre->nama }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge {{ $buku->status == 'tersedia' ? 'bg-success' : 'bg-danger' }} text-white text-xs">
                                                {{ ucwords($buku->status) }}
                                            </span>
                                        </td>

                                        <td class="text-bold-200">{{ $buku->stok }} Buku</td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="{{ route('admin.buku.edit', $buku->idBuku) }}" class="btn btn-sm border text-primary" data-bs-toggle="tooltip" title="Edit Buku">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <form method="POST" action="{{ route('admin.buku.destroy', $buku->idBuku) }}" class="m-0" onsubmit="displayAlert(event, this, '{{ $buku->judul }}', 'warning')">
                                                    @csrf

                                                    <input name="_method" type="hidden" value="DELETE">

                                                    <button class="btn btn-sm border text-danger" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Buku">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form> 
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="coverModal{{ $buku->idBuku }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-dark border-secondary text-white">
                                                <div class="modal-header border-secondary">
                                                    <h5 class="modal-title">{{ $buku->judul }}</h5>

                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body text-center p-4">
                                                    <img src="{{ $buku->photoUrl }}" alt="Cover {{ $buku->judul }}" class="img-fluid rounded shadow-lg" style="max-height: 500px; object-fit: contain;">                    

                                                    <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">Author: {{ $buku->pengarang }}</p>
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
                        @if($bukus->total() <= 10)
                            <nav class="d-flex align-items-center justify-content-between w-100 mt-4" style="width: 100% !important;">
                                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 w-100">
                                    <div class="small text-center text-sm-start text-muted mb-0">
                                        Menampilkan
                                        <span class="fw-semibold">{{ $bukus->firstItem() }}</span>
                                        sampai
                                        <span class="fw-semibold">{{ $bukus->lastItem() }}</span>
                                        dari
                                        <span class="fw-semibold">{{ $bukus->total() }}</span>
                                        hasil
                                    </div>
                                </div>
                            </nav>
                        @else
                            {{ $bukus->links('pagination::bootstrap-5') }}
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts') 
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        const genreSelect = document.getElementById('filter-genre');

        if(genreSelect){
            const choices = new Choices(genreSelect, {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Pilih kategori genre...',
                shouldSort: false,
                itemSelectText: '',
            });
        }            
    </script>
@endpush