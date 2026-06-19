@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bukuDisplay.css') }}">
@endpush

<!-- Memberikan keterangan "Tambah Buku" pada Judul Halaman -->
@section('current-page','Tambah Buku')

@section('content')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <!-- Menampilkan alert error jika ada error input -->
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

                <!-- Form Create Buku -->
                <form class="form" method="POST" action="{{ route('admin.buku.store') }}" enctype="multipart/form-data" onsubmit="tampilLoadingAnimation(this)">
                    <div class="row">

                        <!-- Input Judul Buku -->
                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="judul-buku-column">Judul Buku</label>

                                <div class="position-relative">
                                    <input type="text" id="judul-buku-column" class="form-control mt-1" placeholder="Masukkan judul buku..." name="judul" value="{{ old('judul') }}">

                                    <div class="form-control-icon">
                                        <i class="bi bi-journal-bookmark"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Input Pengarang Buku -->
                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="pengarang-column">Pengarang</label>

                                <div class="position-relative">
                                    <input type="text" id="pengarang-column" class="form-control mt-1" placeholder="Masukkan pengarang buku..." name="pengarang" value="{{ old('pengarang') }}">

                                    <div class="form-control-icon">
                                        <i class="bi bi-person-lines-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Input Penerbit Buku -->
                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="penerbit-column">Penerbit</label>

                                <div class="position-relative">
                                    <input type="text" id="penerbit-column" class="form-control mt-1" placeholder="Masukkan penerbit buku..." name="penerbit" value="{{ old('penerbit') }}">

                                    <div class="form-control-icon">
                                        <i class="bi bi-globe-americas"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Input Tanggal Terbit Buku -->
                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="tanggal-terbit-column">Tanggal Terbit</label>

                                <div class="position-relative">
                                    <input type="text" id="tanggal-terbit-column" class="form-control mb-3 flatpickr-no-config flatpickr-input mt-1" placeholder="Masukkan tanggal terbit buku..." name="tanggalTerbit" readonly="readonly" value="{{ old('tanggalTerbit') }}">

                                    <div class="form-control-icon">
                                        <i class="bi bi-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Input Jumlah Halaman Buku -->
                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="jumlah-halaman-column">Jumlah Halaman</label>

                                <div class="position-relative">
                                    <input type="number" id="jumlah-halaman-column" class="form-control mt-1" placeholder="Masukkan jumlah halaman buku..." name="jumlahHalaman" value="{{ old('jumlahHalaman') }}">

                                    <div class="form-control-icon">
                                        <i class="bi bi-123"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Status Buku (Tersedia/TidakTersedia) -->
                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="status-column" class="form-label">Status</label>                                                                                        

                                <div class="position-relative">
                                    <select id="status-column" class="form-control mt-1" name="status">
                                        <option value="tersedia" {{ old('status') == 'tersedia' ? 'selceted' : ''}}>Tersedia</option>
                                        <option value="tidaktersedia" {{ old('status') == 'tidak tersedia' ? 'selceted' : ''}}>Tidak Tersedia</option>
                                    </select>

                                    <div class="form-control-icon">
                                        <i class="bi bi-cart2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Input Stok Buku -->
                        <div class="col-12">
                            <div class="form-group has-icon-left">
                                <label for="stok-column">Stok</label>

                                <div class="position-relative">
                                    <input type="number" id="stok-column" class="form-control mt-1" placeholder="Masukkan stok buku..." name="stok" value="{{ old('stok') }}">

                                    <div class="form-control-icon">
                                        <i class="bi bi-collection"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Genre dan Bisa Banyak Genre -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="genre-column" class="form-label">Genre</label>  

                                <div class="position-relative">
                                    <select id="genre-column" class="choices form-control form-select mt-1" name="idGenre[]" multiple="multiple" data-placeholder="Pilih genre...">
                                        @foreach($genre as $dataGenre)
                                            <option value="{{ $dataGenre->idGenre }}" {{ old('idGenre') == $dataGenre->idGenre ? 'selected' : '' }}>{{ $dataGenre->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Cover / Foto Buku -->
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="foto-buku" class="form-label">Foto Buku</label>

                                <input type="file" class="image-preview-filepond mb-0" name="photoUrl" accept="image/*" data-max-file-size="2MB">

                                <small class="text-muted">Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB.</small>
                            </div>
                        </div>

                        <!-- Menu Button -->
                        <div class="col-12 d-flex flex-wrap justify-content-end gap-2 mt-3">
                            <!-- Button Kembali ke Manage Buku -->
                            <a href="{{ route('admin.buku.index') }}" class="btn btn-warning text-white align-items-center fw-semibold px-3 py-2">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke List
                            </a>

                            <!-- Button Tambah Buku -->
                            <button type="submit" class="btn btn-primary align-items-center fw-semibold px-3 py-2">
                                <span id="text-button">
                                    <i class="bi bi-pencil-square me-1"></i>Tambah Buku
                                </span>
                                <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                <span id="text-loading" class="d-none">Loading...</span>   
                            </button>

                            <!-- Menu Reset Input -->
                            <button type="reset" class="btn btn-danger text-white align-items-center px-3 py-2">
                                <i class="bi bi-arrow-repeat me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/filepond/filepond.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/filepond.js') }}"></script>
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <!-- Mengubah Select Menu menjadi Choices -->
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