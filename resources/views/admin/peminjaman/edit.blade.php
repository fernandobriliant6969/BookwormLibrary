@extends('admin.layouts.main');

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
                    <div class="row">    
                        <div class="col-lg-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Informasi Peminjam</h4>
                                </div>

                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Nama Anggota</label>
                                        <input type="text" class="form-control" name="namaAnggota" value="{{ $peminjaman->user->nama . ' - ' . $peminjaman->user->username }}" disabled>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Tanggal Pinjam</label>
                                        <input type="text" class="form-control flatpickr-no-config" name="tanggalPinjam" value="{{ $peminjaman->tanggalPeminjaman }}" disabled>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Lama Pinjam (Hari)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="lamaPinjam" value="{{ $peminjaman->lamaPinjam }}" disabled>
                                            <span class="input-group-text">Hari</span>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Catatan / Keterangan</label>
                                        <textarea class="form-control" name="catatan" rows="3" disabled>{{ $peminjaman->catatan }}</textarea>
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
                                        <label class="form-label fw-bold">Daftar Buku yang Dipinjam</label>

                                        <div class="table-responsive mt-2">
                                            <table class="table table-bordered table-striped" id="table-buku-pinjam">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%">No</th>
                                                        <th style="width: 50%" class="text-center">Judul Buku</th>
                                                        <th style="width: 20%" class="text-center">Status</th>
                                                        <th style="width: 25%" class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse($peminjaman->details as $index => $detail)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>

                                                            <td>
                                                                <span class="fw-semibold">{{ $detail->buku->judul }}</span>
                                                            </td>

                                                            <td class="text-center">
                                                                @if($detail->status == 'dipinjam')
                                                                    <span class="badge bg-light-warning text-warning">Dipinjam</span>
                                                                @else
                                                                    <span class="badge bg-light-success text-success">Dikembalikan</span>
                                                                @endif
                                                            </td>

                                                            <td class="text-center">
                                                                @if($detail->status == 'dipinjam')
                                                                    <form action="{{ route('admin.peminjaman.updateDetail', ['peminjaman' => $peminjaman->idPeminjaman, 'detail' => $detail->idDetailPeminjaman]) }}" method="POST" class="d-inline" onsubmit="displayAlert(event, this, 'Apakah anda yakin ingin kembalikan buku ini?','warning')">
                                                                        @csrf

                                                                        <button type="submit" class="btn btn-sm btn-success fw-semibold">
                                                                            Kembalikan Buku
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <button type="submit" class="btn btn-sm btn-success fw-semibold" disabled>
                                                                        Kembalikan Buku
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted py-3">Tidak ada data buku dalam peminjaman ini.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6 col-12 mb-3">
                                            <span class="text-muted d-block mb-1" style="font-size: 0.9rem;">Status Peminjaman</span>

                                            @if($peminjaman->status == 'Aktif')
                                                <span class="badge bg-primary text-white fw-bold px-3 py-2 rounded-pill 6shadow-sm" style="font-size: 0.75rem;">Aktif</span>
                                            @elseif($peminjaman->status == 'Dikembalikan Sebagian')
                                                <span class="badge bg-warning text-white fw-bold px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.75rem;">Dikembalikan Sebagian</span>
                                            @elseif($peminjaman->status == 'Telah Dikembalikan')
                                                <span class="badge bg-success text-white fw-bold px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.75rem;">Telah Dikembalikan</span>
                                            @else
                                                <span class="badge bg-danger text-white fw-bold px-3 py-2 rounded-pill 6shadow-sm" style="font-size: 0.75rem;">Terlambat</span>
                                            @endif
                                        </div>

                                        <div class="col-md-6 col-12 mb-3">
                                            <span class="text-muted d-block mb-1" style="font-size: 0.9rem;">Denda</span>

                                            @if($peminjaman->denda()->exists())
                                                @if($peminjaman->denda->jumlahDenda > 0)
                                                    <h6 class="text-danger fw-bold m-0">Rp. {{ number_format($peminjaman->denda->jumlahDenda, 0, ',', '.') }}</h6>
                                                @else
                                                    <h4 class="text-success fw-bold m-0">Rp. 0</h4>
                                                @endif
                                            @else
                                                <h4 class="text-success fw-bold m-0">Rp. 0</h4>
                                            @endif
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="d-flex flex-wrap justify-content-end gap-2">
                                        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-warning text-white px-3 py-2">
                                            Kembali ke List
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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