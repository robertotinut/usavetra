@extends('layouts.app')
@section('title', 'Insight Bisnis')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Insight Bisnis & Laporan Keuangan</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Insight Bisnis</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        @if(session('premium_warning'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="mdi mdi-information-outline me-2"></i> {{ session('premium_warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('reports.index') }}" method="GET" class="row gy-2 gx-3 align-items-end">
                    <div class="col-sm-auto">
                        <label class="form-label" for="start_date">Mulai Tanggal</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-sm-auto">
                        <label class="form-label" for="end_date">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-sm-auto">
                        <button type="submit" name="action" value="filter" class="btn btn-primary w-md"><i class="bx bx-filter-alt"></i> Filter Laporan</button>
                    </div>
                    <div class="col-sm-auto">
                        <button type="submit" name="action" value="pdf" class="btn btn-danger w-md" formtarget="_blank">
                            @if(!isset($hasPremiumReport) || !$hasPremiumReport)<i class="bx bxs-lock-alt"></i>@else<i class="bx bxs-file-pdf"></i>@endif Cetak PDF
                        </button>
                    </div>
                    <div class="col-sm-auto">
                        <button type="submit" name="action" value="excel" class="btn btn-success w-md">
                            @if(!isset($hasPremiumReport) || !$hasPremiumReport)<i class="bx bxs-lock-alt"></i>@else<i class="bx bx-file"></i>@endif Export Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Omzet -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Omzet Penjualan</span>
                        <h4 class="mb-3">
                            Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                        <div class="avatar-sm mx-auto mb-3 mt-1">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-24">
                                <i class="bx bx-dollar-circle"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Laba Kotor -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Est. Laba Kotor</span>
                        <h4 class="mb-3">
                            <span class="text-success">Rp {{ number_format($labaKotor, 0, ',', '.') }}</span>
                        </h4>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                        <div class="avatar-sm mx-auto mb-3 mt-1">
                            <span class="avatar-title rounded-circle bg-success bg-soft text-success font-size-24">
                                <i class="bx bx-trending-up"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pengeluaran -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Pengeluaran (Beli)</span>
                        <h4 class="mb-3">
                            <span class="text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                        </h4>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                        <div class="avatar-sm mx-auto mb-3 mt-1">
                            <span class="avatar-title rounded-circle bg-danger bg-soft text-danger font-size-24">
                                <i class="bx bx-cart-alt"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transaksi -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Transaksi (Struk)</span>
                        <h4 class="mb-3">
                            {{ number_format($totalTransaksi, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                        <div class="avatar-sm mx-auto mb-3 mt-1">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-24">
                                <i class="bx bx-receipt"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Grafik Omzet Harian</h4>
                <div style="height: 350px;">
                    <canvas id="omzetChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">5 Produk Terlaris</h4>
                
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <tbody>
                            @forelse($topProducts as $idx => $prod)
                            <tr>
                                <td style="width: 40px;">
                                    <div class="avatar-sm">
                                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-14">
                                            {{ $idx + 1 }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <h5 class="font-size-14 m-0 text-truncate" style="max-width: 150px;">{{ $prod->name }}</h5>
                                </td>
                                <td>
                                    <div class="text-end">
                                        <h5 class="font-size-14 m-0">{{ $prod->total_qty }}x</h5>
                                        <small class="text-muted">Rp {{ number_format($prod->total_revenue, 0, ',', '.') }}</small>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada data penjualan pada periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('omzetChart').getContext('2d');
        
        var labels = {!! json_encode($chartLabels) !!};
        var dataValues = {!! json_encode($chartValues) !!};
        
        if (labels.length === 0) {
            labels = ['Tidak ada data'];
            dataValues = [0];
        }

        var gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(85, 110, 230, 0.4)');   
        gradient.addColorStop(1, 'rgba(85, 110, 230, 0.0)');

        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omzet Penjualan (Rp)',
                    data: dataValues,
                    backgroundColor: gradient,
                    borderColor: '#556ee6',
                    borderWidth: 2,
                    pointBackgroundColor: '#556ee6',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#556ee6',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                if(value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                                if(value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
