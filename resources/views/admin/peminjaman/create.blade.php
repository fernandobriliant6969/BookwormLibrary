@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bukuDisplay.css') }}">
@endpush

@section('current-page','Tambah Peminjaman')

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

                <form action="{{ route('admin.peminjaman.store') }}" method="POST" onsubmit="tampilLoadingAnimation(this)">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Informasi Peminjam</h4>
                                </div>

                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Nama Anggota</label>

                                        <select class="choices form-select" name="idUser">
                                            <option value="">Cari Nama Anggota...</option>

                                            @foreach($users as $user)
                                                <option value="{{ $user->idUser }}">{{ $user->nama }} - ({{ $user->username }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Tanggal Pinjam</label>
                                        <input type="text" class="form-control flatpickr-no-config" name="tanggalPinjam" placeholder="Pilih Tanggal..">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Lama Pinjam (Hari)</label>

                                        <div class="input-group">
                                            <input type="number" class="form-control" name="lamaPinjam" min="1" placeholder="Contoh: 7">

                                            <span class="input-group-text">Hari</span>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Catatan / Keterangan <small class="text-muted">(Opsional)</small></label>
                                        <textarea class="form-control" name="keterangan" rows="3" placeholder="Tulis catatan tambahan jika ada..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Buku yang Dipinjam</h4>
                                </div>

                                <div class="card-body">
                                    <div class="form-group mb-4">
                                        <label class="form-label">Pilih Buku</label>

                                        <select class="choices form-select" name="idBuku[]" multiple="multiple" data-placeholder="Pilih buku yang ingin dipinjam...">
                                            @foreach($buku as $dataBuku)
                                                @if($dataBuku->stok > 0)
                                                    <option value="{{ $dataBuku->idBuku }}">{{ $dataBuku->judul }} (Tersedia: {{ $dataBuku->stok }})</option>
                                                @else
                                                    <option value="{{ $dataBuku->idBuku }}" disabled>{{ $dataBuku->judul }} (Tidak Tersedia: 0)</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <hr>

                                    <div class="d-flex flex-wrap justify-content-end gap-2 mt-3">
                                        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-warning text-white align-items-center fw-semibold px-3 py-2">
                                            <i class="bi bi-arrow-left me-1"></i> Kembali ke List
                                        </a>

                                        <button type="submit" class="btn btn-primary align-items-center fw-semibold">
                                            <span id="text-button">
                                                <i class="bi bi-pencil-square me-1"></i>Tambah Peminjaman
                                            </span>
                                            <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                            <span id="text-loading" class="d-none">Loading...</span>   
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script>
        let choicesInput = document.querySelectorAll('.choices');
        let initChoices;
        for (let i = 0; i < choicesInput.length; i++) {
            initChoices = new Choices(choicesInput[i], {
                delimiter: ',',
                editItems: true,
                maxItemCount: -1,
                removeItemButton: true,
                allowHTML: true,
                searchFields: ['label', 'value']
            });
        }
    </script>
@endpush