@extends(auth()->user()->role == 'member' ? 'layouts.main' : 'admin.layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Update Password</h5>
                </div>

                <div class="card-body">
                    @if($errors->passwordError->any())
                        <div class="alert alert-danger alert-dismissible show fade pb-1" role="alert">
                            <ul>
                                @foreach($errors->passwordError->all() as $error)
                                    <li>{{  $error }}</li>
                                @endforeach 
                            </ul> 

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.security.updatePassword', auth()->user()->idUser) }}" method="POST" onsubmit="tampilLoadingAnimation(this)">
                        @csrf
                        @method('PUT')

                        <div class="form-group my-2">
                            <label for="current_password" class="form-label">Password saat ini</label>

                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Masukkan password saat ini">
                        </div>

                        <div class="form-group my-2">
                            <label for="password" class="form-label">Password baru</label>

                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password baru">
                        </div>

                        <div class="form-group my-2">
                            <label for="password_confirmation" class="form-label">Password baru untuk konfirmasi</label>

                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                        </div>

                        <div class="form-group mt-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary fw-semibold">
                                <span id="text-button">
                                    <i class="bi bi-shield-lock me-1"></i> Update Password
                                </span>
                                <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                <span id="text-loading" class="d-none">Loading...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Update Email</h5>
                </div>

                <div class="card-body">
                    @if($errors->emailError->any())
                        <div class="alert alert-danger alert-dismissible show fade pb-1" role="alert">
                            <ul>
                                @foreach($errors->emailError->all() as $error)
                                    <li>{{  $error }}</li>
                                @endforeach 
                            </ul> 

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.security.updateEmail', auth()->user()->idUser) }}" method="POST" onsubmit="tampilLoadingAnimation(this)">
                        @csrf
                        @method('PUT')

                        <div class="form-group my-2">
                            <label for="email" class="form-label">Email Baru</label>

                            <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email baru" value="{{ old('email', auth()->user()->email) }}">
                        </div>

                        <div class="form-group my-2">
                            <label for="password_verify" class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_verify" id="password_verify" class="form-control" placeholder="Masukkan password saat ini untuk verifikasi ganti email">
                        </div>

                        <div class="form-group mt-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary fw-semibold">
                                <span id="text-button">
                                    <i class="bi bi-envelope-check me-1"></i> Update Email
                                </span>
                                <div id="spinner-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></div>                   
                                <span id="text-loading" class="d-none">Loading...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Hapus Akun</h5>
                </div>

                <div class="card-body">
                    <p class="text-danger fw-semibold">Akun Anda akan dihapus secara permanen dari sistem perpustakaan dan tidak bisa dikembalikan.</p>

                    <div class="form-check">
                        <div class="checkbox">
                            <input type="checkbox" id="iaggree" class="form-check-input">

                            <label for="iaggree" class="text-muted">Saya setuju untuk menghapus akun ini secara permanen</label>
                        </div>
                    </div>

                    <div class="form-group my-2 d-flex justify-content-end">
                        <form method="POST" action="{{ route('profile.security.deleteAccount', auth()->user()->idUser) }}" onsubmit="displayAlert(event, this, 'Apakah anda yakin ingin menghapus akun ini? Semua riwayat peminjaman, review dan aktivitas anda akan hilang selamanya!', 'warning')">
                            @csrf

                            <input name="_method" type="hidden" value="DELETE">

                            <button type="submit" class="btn btn-danger" id="btn-delete-account" disabled>Hapus Permanent</button>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const checkboxAgree = document.getElementById('iaggree');
        const btnDeleteAccount = document.getElementById('btn-delete-account');
        
        if (checkboxAgree && btnDeleteAccount) {
            checkboxAgree.addEventListener('change', function () {
                btnDeleteAccount.disabled = !this.checked;
            });
        }
    </script>
@endpush