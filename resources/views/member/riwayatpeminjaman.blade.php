@extends('layouts.main')

@section('current-page','Riwayat Peminjaman')

@section('content')
    <div class="page-heading">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card">
                        @if($peminjaman->isEmpty())
                            <div class="card-body d-flex align-items-center">
                                <i class="bi bi-info-circle d-inline-flex me-3 align-items-center"></i>
                                <span>Belum ada peminjaman, Yuk lakukan peminjaman pertama!</span>
                            </div>
                        @else
                            <div class="card-header d-lg-none">
                                <h4 class="card-title">Riwayat Peminjaman</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive pt-4">
                                    <table class="table table-hover align-middle mb-0" id="table-peminjaman">
                                        <thead class="table border-bottom border-secondary-subtle">
                                            <tr>
                                                <th style="width: 5%" class="py-3 text-center">NO</th>
                                                <th style="width: 55%" class="py-3">BUKU YANG DIPINJAM</th>
                                                <th style="width: 20%" class="py-3">PERIODE PINJAM</th>
                                                <th style="width: 10%" class="py-3 text-center">DENDA</th>
                                                <th style="width: 10%" class="py-3 text-center">STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($peminjaman as $index => $pinjam)
                                                <tr class="border-bottom border-light-subtle">
                                                    <td class="text-center fw-semibold text-secondary">{{ $peminjaman->firstItem() + $index }}</td>
                                                    <td>
                                                        <ul class="list-group list-group-flush mb-0 bg-transparent">
                                                            @foreach($pinjam->details as $detail)
                                                                <li class="list-group-item d-flex justify-content-between align-items-center p-2 bg-transparent border-0">
                                                                    <div class="d-flex align-items-center me-3 text-truncate" style="max-width: 75%;">
                                                                        
                                                                        <div class="flex-shrink-0 me-2">
                                                                            <img src="{{ $detail->buku->photoUrl ?? 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=200&auto=format&fit=crop&q=60' }}" 
                                                                                alt="Cover {{ $detail->buku->judul }}" 
                                                                                class="rounded shadow-sm border border-secondary border-opacity-25"
                                                                                style="width: 32px; height: 45px; object-fit: cover;">
                                                                        </div>

                                                                        <div class="text-truncate fw-medium text-dark-theme-white" style="font-size: 0.88rem;" title="{{ $detail->buku->judul }}">
                                                                            {{ $detail->buku->judul }}
                                                                        </div>
                                                                    </div>
                                                                        
                                                                    <div class="flex-shrink-0">
                                                                        @if($detail->status == 'dipinjam')
                                                                            <span class="badge bg-primary text-white fw-semibold px-2 py-1" style="font-size: 0.72rem;">Dipinjam</span>
                                                                        @else
                                                                            <span class="badge bg-success text-white fw-semibold px-2 py-1" style="font-size: 0.72rem;">Dikembalikan</span>
                                                                        @endif
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    
                                                    <td>
                                                        <div style="font-size: 0.85rem;" class="lh-sm">
                                                            <span class="d-block fw-semibold text-success-emphasis"><i class="bi bi-calendar-plus me-1"></i> {{ \Carbon\Carbon::parse($pinjam->tanggalPeminjaman)->format('d M Y') }}</span>
                                                            <span class="d-block mt-1 fw-semibold text-danger-emphasis"><i class="bi bi-calendar-minus me-1"></i> {{ \Carbon\Carbon::parse($pinjam->tanggalKembali)->format('d M Y') }}</span>
                                                            <span class="badge bg-secondary-subtle text-secondary-emphasis mt-2 fw-medium px-2 py-1">{{ $pinjam->lamaPinjam }} Hari</span>
                                                        </div>
                                                    </td>
                                                        
                                                    <td class="text-center">
                                                        @if($pinjam->denda()->exists())
                                                            @if($pinjam->denda->jumlahDenda > 0)
                                                                <span class="badge bg-danger text-white fw-bold px-3 py-2 rounded-pill 6shadow-sm" style="font-size: 0.75rem;">Rp. {{ number_format($pinjam->denda->jumlahDenda, 0, ',', '.') }}</span>
                                                            @else
                                                                <span class="badge bg-success text-white fw-bold px-3 py-2 rounded-pill 6shadow-sm" style="font-size: 0.75rem;">Rp. 0</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-success text-white fw-bold px-3 py-2 rounded-pill 6shadow-sm" style="font-size: 0.75rem;">Rp. 0</span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        @if($pinjam->status == 'Aktif')
                                                            <span class="badge bg-primary text-white fw-bold px-3 py-2 rounded-pill 6shadow-sm" style="font-size: 0.75rem;">Aktif</span>
                                                        @elseif($pinjam->status == 'Dikembalikan Sebagian')
                                                            <span class="badge bg-warning text-white fw-bold px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.75rem;">Dikembalikan Sebagian</span>
                                                        @elseif($pinjam->status == 'Telah Dikembalikan')
                                                            <span class="badge bg-success text-white fw-bold px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.75rem;">Telah Dikembalikan</span>
                                                        @else
                                                            <span class="badge bg-danger text-white fw-bold px-3 py-2 rounded-pill 6shadow-sm" style="font-size: 0.75rem;">Terlambat</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-center align-items-center mt-3">
                                    @if($peminjaman->total() <= 10)
                                        <nav class="d-flex align-items-center justify-content-between w-100 mt-4" style="width: 100% !important;">
                                            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 w-100">
                                                <div class="small text-center text-sm-start text-muted mb-0">
                                                    Menampilkan
                                                    <span class="fw-semibold">{{ $peminjaman->firstItem() }}</span>
                                                    sampai
                                                    <span class="fw-semibold">{{ $peminjaman->lastItem() }}</span>
                                                    dari
                                                    <span class="fw-semibold">{{ $peminjaman->total() }}</span>
                                                    hasil
                                                </div>
                                            </div>
                                        </nav>
                                    @else
                                        {{ $peminjaman->links('pagination::bootstrap-5') }}
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection