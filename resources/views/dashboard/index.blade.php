@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-2">Dashboard</h1>
        <p class="text-muted">Selamat datang di sistem manajemen rental kendaraan</p>
    </div>

    <!-- Welcome Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="avatar-circle bg-primary me-3" style="width: 60px; height: 60px; font-size: 24px;">
                        O
                    </div>
                    <div>
                        <h5 class="mb-1">Welcome</h5>
                        <p class="text-muted mb-0">Owner</p>
                    </div>
                </div>
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-sign-out-alt me-2"></i>Sign out
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Kendaraan</p>
                            <h3 class="mb-0">{{ $stats['total_kendaraan'] }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-car fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Kendaraan Tersedia</p>
                            <h3 class="mb-0">{{ $stats['kendaraan_tersedia'] }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Pelanggan</p>
                            <h3 class="mb-0">{{ $stats['total_pelanggan'] }}</h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Transaksi</p>
                            <h3 class="mb-0">{{ $stats['total_transaksi'] }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-exchange-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart Section -->
        <div class="col-md-7 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Pendapatan 7 Hari Terakhir</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Tambah Pelanggan Baru
                        </a>
                        <a href="#" class="btn btn-outline-success btn-lg">
                            <i class="fas fa-car-side me-2"></i>Tambah Kendaraan Baru
                        </a>
                        <a href="#" class="btn btn-outline-info btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Buat Transaksi Rental
                        </a>
                        <a href="#" class="btn btn-outline-warning btn-lg">
                            <i class="fas fa-file-alt me-2"></i>Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 5 Kendaraan Terpopuler -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Top 5 Kendaraan Terpopuler</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">Merek</th>
                            <th class="px-4 py-3">Model</th>
                            <th class="px-4 py-3">Plat Nomor</th>
                            <th class="px-4 py-3">Total Disewa</th>
                            <th class="px-4 py-3">Harga/Hari</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topKendaraan as $kendaraan)
                        <tr>
                            <td class="px-4 py-3">{{ $kendaraan['merek'] }}</td>
                            <td class="px-4 py-3">{{ $kendaraan['model'] }}</td>
                            <td class="px-4 py-3">{{ $kendaraan['plat_nomor'] }}</td>
                            <td class="px-4 py-3">
                                <span class="badge bg-primary rounded-pill">
                                    {{ $kendaraan['total_disewa'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                IDR {{ number_format($kendaraan['harga_per_hari'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-success">{{ $kendaraan['status'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Revenue Chart
const ctx = document.getElementById('revenueChart');
const chartData = @json($chartData);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartData.labels,
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: chartData.data,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#3b82f6',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    color: '#fff'
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#9ca3af',
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            },
            x: {
                ticks: {
                    color: '#9ca3af'
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            }
        }
    }
});
</script>
@endpush