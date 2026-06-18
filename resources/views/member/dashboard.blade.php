@extends('layouts.main')

@section('current-page','Dashboard Member')

@push('styles')
    <style>
        .table-responsive {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                @if($isTerlambat)
                    <div class="alert alert-danger alert-dismissible show fade mb-4 ms-2 p-3">                    
                        <table style="width: 100%; border: none; background: transparent; border-collapse: collapse;">
                            <tr>
                                <td style="width: 40px; vertical-align: middle; padding: 0; border: none; background: transparent;">
                                    <i class="bi bi-exclamation-triangle-fill fs-4" style="display: inline-block; line-height: 1; margin: 0;"></i>
                                </td>
                                    
                                <td style="vertical-align: middle; padding: 0 30px 0 10px; border: none; background: transparent; color: inherit;">
                                    Kamu memiliki <b>{{ $totalPeminjamanTerlambat }} Peminjaman</b> dengan total <b>{{ $totalBukuTerlambat }} buku</b> yang sudah melewati tanggal kembali. Harap segera lakukan pengembalian buku dan membayar denda, Terimakasih.
                                </td>
                            </tr>
                        </table>
                            
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="top: 50%; transform: translateY(-50%);"></button>
                    </div>
                @endif
            </div>
        </div>

        <div class="row g-2">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon blue me-3">
                                <i class="bi bi-journal-bookmark text-white d-inline-flex align-items-center me-2"></i>
                            </div>

                            <div>
                                <h6 class="text-muted mb-1">Total Buku Peminjaman</h6>
                                <h6 class="font-extrabold mb-0">{{ $totalBukuPeminjaman }} Buku</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon red me-3">
                                <i class="bi bi-book-half text-white d-inline-flex align-items-center me-2"></i>
                            </div>

                            <div>
                                <h6 class="text-muted mb-1">Jumlah Buku Sedang Dipinjam</h6>
                                <h6 class="font-extrabold mb-0">{{ $totalBukuBelumDikembalikan }} Buku</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon purple me-3">
                                <i class="bi bi-pass-fill text-white d-inline-flex align-items-center me-2"></i>
                            </div>

                            <div>
                                <h6 class="text-muted mb-1">Total Peminjaman</h6>
                                <h6 class="font-extrabold mb-0">{{ $totalPeminjaman }} Peminjaman</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h4>Aktivitas Peminjaman - 30 Hari Terakhir</h4>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="chart-peminjaman-bulan"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h4>Genre Terfavorit</h4>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="chart-donut-genre"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h4 class="card-title"><i class="bi bi-fire text-danger me-2 mb-1"></i>Top 5 Buku Terpopuler</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 custom-dash-table">
                                <thead>
                                    <tr>
                                        <th style="width: 15%" class="text-center">RANK</th>
                                        <th style="width: 85%">BUKU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bukuPopuler as $index => $buku)
                                        <tr class="border-bottom border-light-subtle">
                                            <td class="text-center fw-bold">
                                                @if($index == 0)
                                                    <span class="fs-4">🏆</span>
                                                @elseif($index == 1)
                                                    <span class="fs-4">🥈</span>
                                                @elseif($index == 2)
                                                    <span class="fs-4">🥉</span>
                                                @else
                                                    <span class="text-secondary ms-1" style="font-size: 0.9rem;">#{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td> 
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ $buku->photoUrl ?? 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=200&auto=format&fit=crop&q=60' }}" 
                                                        alt="Cover" class="rounded shadow-sm border border-secondary border-opacity-10 dash-book-img" 
                                                        style="width: 32px; height: 46px; object-fit: cover; flex-shrink: 0;">

                                                    <div class="flex-grow-1">
                                                        <div class="fw-semibold text-dark-theme-white dash-book-title" title="{{ $buku->judul }}">
                                                            {{ $buku->judul }}
                                                        </div>
                                                        <small class="text-muted d-block" style="font-size: 0.72rem;">Dipinjam {{ $buku->detail_peminjamans_count }} kali</small>
                                                    </div>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h4 class="card-title"><i class="bi bi-star-fill text-warning me-2"></i>Top 5 Review Tertinggi</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 custom-dash-table">
                                <thead>
                                    <tr>
                                        <th style="width: 15%" class="text-center">NO</th>
                                        <th style="width: 60%">BUKU</th>
                                        <th style="width: 25%" class="text-center">RATING</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bukuRatingTertinggi as $index => $buku)
                                    <tr class="border-bottom border-light-subtle">
                                        <td class="text-center fw-bold">
                                            @if($index == 0)
                                                <span class="fs-4">🏆</span>
                                            @elseif($index == 1)
                                                <span class="fs-4">🥈</span>
                                            @elseif($index == 2)
                                                <span class="fs-4">🥉</span>
                                            @else
                                                <span class="text-secondary ms-1" style="font-size: 0.9rem;">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td> 
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $buku->photoUrl ?? 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=200&auto=format&fit=crop&q=60' }}" 
                                                    alt="Cover" class="rounded shadow-sm border border-secondary border-opacity-10 dash-book-img" 
                                                    style="width: 32px; height: 46px; object-fit: cover; flex-shrink: 0;">

                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold text-dark-theme-white dash-book-title" data-bs-toggle="tooltip" title="{{ $buku->judul }}">
                                                        {{ $buku->judul }}
                                                    </div>
                                                    <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $buku->review_count }} Review</small>
                                                </div>

                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center justify-content-center px-2 py-1 rounded bg-warning bg-opacity-10 border border-warning border-opacity-25">
                                                <i class="bi bi-star-fill text-warning me-1 d-inline-flex"></i>
                                                <span class="text-warning fw-bold mt-1" style="font-size: 0.9rem;">
                                                    {{ number_format($buku->rating_avg, 1) ?? '0.0' }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const labelTanggal = {!! json_encode($chartLabels) !!};
        const dataTotal = {!! json_encode($chartData) !!};

        const ctx = document.getElementById('chart-peminjaman-bulan').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(67, 94, 190, 0.35)');
        gradient.addColorStop(1, 'rgba(67, 94, 190, 0.01)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelTanggal,
                datasets: [{
                    label: 'Buku Dipinjam',
                    data: dataTotal,
                    borderColor: '#435ebe',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: gradient,
                    pointBackgroundColor: '#435ebe',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false } 
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6c757d', font: { size: 11 } }
                    },
                    y: {
                        grid: { color: 'rgba(108, 117, 125, 0.1)' },
                        ticks: { 
                            color: '#6c757d',
                            stepSize: 1,
                            beginAtZero: true 
                        }
                    }
                }
            }
        });

        const labelGenre = {!! json_encode($donutLabels) !!};
        const dataGenre = {!! json_encode($donutValues) !!};

        const ctxDonut = document.getElementById('chart-donut-genre').getContext('2d');

        const finalLabels = labelGenre.length > 0 ? labelGenre : ['Belum ada data'];
        const finalData = dataGenre.length > 0 ? dataGenre : [1];
        const finalColors = labelGenre.length > 0 
            ? ['#435ebe', '#198754', '#0dcaf0', '#ffc107', '#fd7e14', '#6f42c1'] 
            : ['#6c757d'];

        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: finalLabels,
                datasets: [{
                    data: finalData,
                    backgroundColor: finalColors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#6c757d',
                            boxWidth: 12,
                            font: { size: 11, family: 'Nunito, sans-serif' },
                            padding: 15
                        }
                    },
                    tooltip: {
                        theme: 'dark',
                        callbacks: {
                            label: function (context) {
                                if (labelGenre.length === 0) return ' Belum ada peminjaman';
                                
                                let sum = context.dataset.data.reduce((a, b) => a + b, 0);
                                let value = context.raw;
                                let percentage = ((value / sum) * 100).toFixed(1);
                                return ` ${context.label}: ${value} Buku (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
@endpush