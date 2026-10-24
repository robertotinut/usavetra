@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">SaaS Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">SaaS Admin</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-7">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Toko / UMKM</span>
                        <h4 class="mb-3"><span class="counter-value" data-target="{{ $totalStores }}">{{ $totalStores }}</span></h4>
                    </div>
                    <div class="col-5">
                        <i data-feather="briefcase" class="text-primary" style="width: 44px; height: 44px; float: right;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-7">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Pengguna</span>
                        <h4 class="mb-3"><span class="counter-value" data-target="{{ $totalUsers }}">{{ $totalUsers }}</span></h4>
                    </div>
                    <div class="col-5">
                        <i data-feather="users" class="text-success" style="width: 44px; height: 44px; float: right;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-7">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Pengguna Online</span>
                        <h4 class="mb-3 text-success d-flex align-items-center gap-2">
                            <span>{{ $onlineUsers }}</span>
                            <span class="badge bg-success-subtle text-success font-size-11 fw-normal">
                                <span class="spinner-grow spinner-grow-sm text-success me-1" role="status" style="width: 6px; height: 6px;"></span> Live
                            </span>
                        </h4>
                    </div>
                    <div class="col-5">
                        <i data-feather="activity" class="text-success" style="width: 44px; height: 44px; float: right;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-7">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Langganan Aktif</span>
                        <h4 class="mb-3"><span class="counter-value" data-target="{{ $activeSubscriptions }}">{{ $activeSubscriptions }}</span></h4>
                    </div>
                    <div class="col-5">
                        <i data-feather="check-circle" class="text-danger" style="width: 44px; height: 44px; float: right;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Shortcut & Feed Aktivitas Terkini -->
<div class="row mt-2">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 font-size-15">
                    <i class="mdi mdi-receipt-text-clock text-primary me-1"></i> Transaksi Terkini Seluruh Platform
                </h5>
                <a href="{{ route('admin.activities.index', ['tab' => 'sales']) }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua Aktivitas <i class="mdi mdi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Struk</th>
                                <th>Toko UMKM</th>
                                <th>Total</th>
                                <th>Metode Bayar</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                            <tr>
                                <td><span class="fw-bold font-monospace text-primary">{{ $sale->receipt_number }}</span></td>
                                <td><span class="badge bg-light text-dark border">{{ $sale->store_name }}</span></td>
                                <td><span class="fw-semibold text-success">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span></td>
                                <td><span class="badge bg-info-subtle text-info">{{ $sale->payment_method }}</span></td>
                                <td><small class="text-muted">{{ \Carbon\Carbon::parse($sale->created_at)->diffForHumans() }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card bg-primary text-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-sm rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                        <i class="mdi mdi-monitor-eye font-size-24"></i>
                    </div>
                    <div>
                        <h5 class="text-white mb-0">Pusat Monitoring</h5>
                        <small class="text-white-50">SaaS Live Tracker</small>
                    </div>
                </div>
                <p class="text-white-50 font-size-13 mb-3">
                    Pantau aktivitas seluruh pengguna, sesi login kasir/owner, transaksi live, dan pergerakan inventaris seluruh toko secara real-time.
                </p>
                <a href="{{ route('admin.activities.index') }}" class="btn btn-light w-100 fw-semibold text-primary">
                    <i class="mdi mdi-chart-timeline-variant me-1"></i> Buka Monitoring Aktivitas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
