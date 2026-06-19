@extends('admin.layouts.main')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-content">
                <div class="row">    
                    <!-- Bagian I : Informasi Peminjam -->
                    <div class="col-lg-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Informasi Peminjam</h4>
                            </div>

                            <div class="card-body">
                                <!-- Menamppilkan Nama Anggota -->
                                <div class="form-group mb-3">
                                    <label class="form-label">Nama Anggota</label>
                                    <input type="text" class="form-control" name="namaAnggota" value="{{ $peminjaman->user->nama . ' - ' . $peminjaman->user->username }}" disabled>
                                </div>

                                <!-- Menampilkan Tanggal Pinjam -->
                                <div class="form-group mb-3">
                                    <label class="form-label">Tanggal Pinjam</label>
                                    <input type="text" class="form-control flatpickr-no-config" name="tanggalPinjam" value="{{ $peminjaman->tanggalPeminjaman }}" disabled>
                                </div>

                                <!-- Menampilkan Lama Pinjam -->
                                <div class="form-group mb-3">
                                    <label class="form-label">Lama Pinjam (Hari)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="lamaPinjam" value="{{ $peminjaman->lamaPinjam }}" disabled>
                                        <span class="input-group-text">Hari</span>
                                    </div>
                                </div>

                                <!-- Menampilkan Catatan / Keterangan -->
                                <div class="form-group mb-3">
                                    <label class="form-label">Catatan / Keterangan</label>
                                    <textarea class="form-control" name="catatan" rows="3" disabled>{{ $peminjaman->catatan }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian II : Buku yang Dipinjam -->
                    <div class="col-lg-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Buku yang Dipinjam</h4>
                            </div>

                            <div class="card-body">
                                <div class="form-group mb-4">
                                    <!-- Menampillkan Daftar Buku yang dipinjam -->
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
                                                @foreach($peminjaman->details as $index => $detail)
                                                    <tr>
                                                        <!-- Menampilkan Sistem Penomoran Baris -->
                                                        <td>{{ $index + 1 }}</td>

                                                        <!-- Menampilkan Judul Buku -->
                                                        <td>
                                                            <span class="fw-semibold">{{ $detail->buku->judul }}</span>
                                                        </td>

                                                        <!-- Menampilkan Status Buku (Dipinjam/Dikembalikan) -->
                                                        <td class="text-center">
                                                            @if($detail->status == 'dipinjam')
                                                                <span class="badge bg-light-warning text-warning">Dipinjam</span>
                                                            @else
                                                                <span class="badge bg-light-success text-success">Dikembalikan</span>
                                                            @endif
                                                        </td>

                                                        <!-- Menu Button -->
                                                        <td class="text-center">
                                                            <!-- Jika Status Buku Dipinjam, Maka muncul button untuk Kembalikan Buku -->
                                                            @if($detail->status == 'dipinjam')
                                                                <!-- Form untuk Update Detail (Mengembalikan Buku - Karena 1 Peminjaman bisa punya banyak peminjaman dan 1 Peminjaman hanya mempunyai 1 buku) -->
                                                                <form action="{{ route('admin.peminjaman.updateDetail', ['peminjaman' => $peminjaman->idPeminjaman, 'detail' => $detail->idDetailPeminjaman]) }}" method="POST" class="d-inline" onsubmit="displayAlert(event, this, 'Apakah anda yakin ingin kembalikan buku ini?','warning')">
                                                                    @csrf

                                                                    <button type="submit" class="btn btn-sm btn-success fw-semibold">
                                                                        Kembalikan Buku
                                                                    </button>
                                                                </form>
                                                            <!-- Jika Status Buku Dikembalikan, Maka Button Kembalikan Buku dimatikan -->
                                                            @else
                                                                <button type="submit" class="btn btn-sm btn-success fw-semibold" disabled>
                                                                    Kembalikan Buku
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <!-- Status Peminjaman
                                        1. Aktif - Belum ada buku yang dikembalikan
                                        2. Dikembalikan Sebagian - Sebagian buku sudah dikembalikan
                                        3. Telah Dikembalikan - Semua buku yang dipinjam sudah dikembalikan
                                        4. Terlambat - Ada buku yang belum dikembalikan dan melewati tanggal kembali -->
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

                                    <!-- Menampilkan Total Nominal Denda
                                        Cara Kerja Sistem Denda:
                                        - Dihitung per hari per buku sebesar 25 ribu dan terakumulasi selama belum dikembalikan
                                        - Jika buku yang terlambat sudah dikembalikan, maka sistem stop akumulasi denda

                                        Contoh: Terlambat 2 hari dan ada 2 buku yang terlambat
                                        Total: 2 hari x 2 buku x 25000 = 100000 (100k) -->
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

                                <!-- Button untuk Kembali ke Manage Peminjaman -->
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
@endsection