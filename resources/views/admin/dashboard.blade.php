@extends('admin.layouts.main')

@section('current-page','Dashboard Perpustakaan')

@section('content')
    <div class="row g-2">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon purple me-3">
                            <i class="bi bi-star-fill text-white d-inline-flex align-items-center me-2"></i>
                        </div>

                        <div>
                            <h6 class="text-muted mb-1">Jumlah Review</h6>
                            <h6 class="font-extrabold mb-0">{{ $jumlahRating }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon blue me-3">
                            <i class="bi bi-clipboard-data-fill text-white d-inline-flex align-items-center me-2"></i>
                        </div>

                        <div>
                            <h6 class="text-muted mb-1">Jumlah Peminjaman</h6>
                            <h6 class="font-extrabold mb-0">{{ $jumlahPeminjaman }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon green me-3">
                            <i class="bi bi-arrow-repeat text-white d-inline-flex align-items-center me-2"></i>
                        </div>

                        <div>
                            <h6 class="text-muted mb-1">Jumlah Peminjaman Dikembalikan</h6>
                            <h6 class="font-extrabold mb-0">{{ $jumlahDikembalikan }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header text-center">
                    <h4>Statistik Perpustakaan</h4>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="position: relative; height: 400px; width: 100%;">
                        <canvas id="mainDashboardChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header text-center">
                    <h4>Aktivitas Peminjaman - 30 Hari Terakhir</h4>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="position: relative; height: 400px; width: 100%;">
                        <canvas id="aktivitasPeminjamanChart"></canvas>
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
                <div class="card-header text-center">
                    <h4>Status Ketersediaan Stok Buku</h4>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="position: relative; height: 400px; width: 100%;">
                        <canvas id="statusKetersediaanStokBukuChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const ctx1 = document.getElementById('mainDashboardChart').getContext('2d');
            
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Total Anggota', 'Total Genre', 'Total Buku', 'Total Peminjaman'],
                datasets: [{
                    label: 'Jumlah Data',
                    data: [
                        {{ $jumlahAnggota }}, 
                        {{ $jumlahGenre }}, 
                        {{ $jumlahBuku }}, 
                        {{ $jumlahPeminjaman }}
                    ],
                    backgroundColor: [
                        '#435ebe',
                        '#198754',
                        '#ffc107',
                        '#dc3545'
                    ],
                    borderRadius: 3,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#b0b0bf'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#ffffff',
                            font: {
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });

        const labelTanggal = {!! json_encode($chartLabelTotalPeminjaman) !!};
        const dataTotal = {!! json_encode($chartDataTotalPeminjaman) !!};

        const ctx2 = document.getElementById('aktivitasPeminjamanChart').getContext('2d');

        const gradient = ctx2.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(67, 94, 190, 0.35)');
        gradient.addColorStop(1, 'rgba(67, 94, 190, 0.01)');

        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: labelTanggal,
                datasets: [{
                    label: 'Total Peminjaman',
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

        const labelsPie = {!! json_encode($pieLabels) !!};
        const dataPie = {!! json_encode($pieValues) !!};

        const ctxPie3 = document.getElementById('statusKetersediaanStokBukuChart').getContext('2d');
        
        new Chart(ctxPie3, {
            type: 'pie',
            data: {
                labels: labelsPie,
                datasets: [{
                    data: dataPie,
                    backgroundColor: ['#198754', '#435ebe', '#dc3545'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6
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
                            font: { size: 12, family: 'Nunito, sans-serif' },
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let value = context.raw;
                                let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return ` ${context.label}: ${value} Eksemplar (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
