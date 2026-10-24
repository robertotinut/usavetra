@extends('layouts.app')
@section('title', 'Monitoring Aktivitas Pengguna')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Monitoring Aktivitas Pengguna</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">SaaS Admin</a></li>
                    <li class="breadcrumb-item active">Monitoring Aktivitas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Metrik Cards (Skote Clean Style) -->
<div class="row">
    <!-- Card 1: Online -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Pengguna Online</span>
                        <h4 class="mb-2">
                            <span class="counter-value" data-target="{{ $onlineUsersCount }}">{{ $onlineUsersCount }}</span>
                            <span class="badge bg-success-subtle text-success font-size-11 ms-1">
                                <i class="mdi mdi-circle-medium"></i> Live
                            </span>
                        </h4>
                        <div class="text-nowrap">
                            <span class="text-muted font-size-12">Aktif 15 menit terakhir</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-success-subtle text-success font-size-22">
                                <i class="bx bx-user-check"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Toko Aktif -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Toko Aktif Hari Ini</span>
                        <h4 class="mb-2">
                            <span class="counter-value" data-target="{{ $activeStoresToday }}">{{ $activeStoresToday }}</span>
                            <span class="text-muted font-size-14 fw-normal">/ {{ $totalStoresCount }} Toko</span>
                        </h4>
                        <div class="text-nowrap">
                            <span class="text-muted font-size-12">Mencatat penjualan hari ini</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-info-subtle text-info font-size-22">
                                <i class="bx bx-store-alt"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Transaksi Hari Ini -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Transaksi Hari Ini</span>
                        <h4 class="mb-2">
                            <span class="counter-value" data-target="{{ $todaySalesCount }}">{{ $todaySalesCount }}</span> Nota
                        </h4>
                        <div class="text-nowrap">
                            <span class="text-primary font-size-13 fw-semibold">Rp {{ number_format($todaySalesSum, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-22">
                                <i class="bx bx-receipt"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Total User Terdaftar -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Akun Terdaftar</span>
                        <h4 class="mb-2">
                            <span class="counter-value" data-target="{{ $totalUsersCount }}">{{ $totalUsersCount }}</span>
                        </h4>
                        <div class="text-nowrap">
                            <span class="text-muted font-size-12">Super Admin, Owner & Kasir</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-warning-subtle text-warning font-size-22">
                                <i class="bx bx-group"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Toko -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.activities.index') }}" class="row g-3 align-items-center">
                    <div class="col-md-5 col-sm-6">
                        <div class="d-flex align-items-center gap-2">
                            <label class="form-label mb-0 text-nowrap fw-medium"><i class="bx bx-filter-alt me-1"></i> Filter Toko:</label>
                            <select name="store_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">-- Semua Toko UMKM --</option>
                                @foreach($stores as $st)
                                    <option value="{{ $st->id }}" {{ $selectedStoreId == $st->id ? 'selected' : '' }}>
                                        {{ $st->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-6 text-sm-end">
                        @if($selectedStoreId)
                            <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-outline-secondary me-1">
                                <i class="bx bx-reset"></i> Reset Filter
                            </a>
                        @endif
                        <button type="button" class="btn btn-sm btn-soft-primary" onclick="window.location.reload()">
                            <i class="bx bx-refresh"></i> Refresh Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Tabs with DataTables -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs nav-tabs-custom mb-4" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-sessions" role="tab">
                            <i class="bx bx-user-check me-1 align-middle font-size-16"></i>
                            <span>Sesi & Pengguna Online</span>
                            <span class="badge bg-success-subtle text-success ms-1">{{ $sessions->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-sales" role="tab">
                            <i class="bx bx-cart me-1 align-middle font-size-16"></i>
                            <span>Transaksi Penjualan</span>
                            <span class="badge bg-primary-subtle text-primary ms-1">{{ $recentSales->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-stocks" role="tab">
                            <i class="bx bx-transfer-alt me-1 align-middle font-size-16"></i>
                            <span>Mutasi Stok Inventaris</span>
                            <span class="badge bg-info-subtle text-info ms-1">{{ $recentStockMovements->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-audit" role="tab">
                            <i class="bx bx-shield-quarter me-1 align-middle font-size-16"></i>
                            <span>Audit Log Sistem</span>
                            <span class="badge bg-warning-subtle text-warning ms-1">{{ $auditLogs->count() }}</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab Panes -->
                <div class="tab-content">
                    <!-- PANE 1: Sesi Pengguna -->
                    <div class="tab-pane active" id="tab-sessions" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table datatable align-middle table-nowrap table-hover w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pengguna</th>
                                        <th>Toko / UMKM</th>
                                        <th>Status Sesi</th>
                                        <th>Perangkat & Browser</th>
                                        <th>Alamat IP</th>
                                        <th>Aktivitas Terakhir</th>
                                        <th class="text-center" data-orderable="false">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sessions as $session)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold font-size-13">
                                                        {{ strtoupper(substr($session->user_name ?? 'U', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h5 class="font-size-14 mb-0 text-dark">{{ $session->user_name ?? 'Tamu' }}</h5>
                                                    <small class="text-muted">{{ $session->user_email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark font-size-12 border">
                                                {{ $session->store_name ?? 'SaaS Pusat' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($session->status === 'online')
                                                <span class="badge bg-success-subtle text-success font-size-12">
                                                    <i class="mdi mdi-circle-medium"></i> Online
                                                </span>
                                            @elseif($session->status === 'idle')
                                                <span class="badge bg-warning-subtle text-warning font-size-12">
                                                    <i class="mdi mdi-clock-outline"></i> Baru Saja
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary font-size-12">
                                                    Offline
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center font-size-13">
                                                <i class="mdi {{ $session->device_info['icon'] }} font-size-16 text-primary me-1"></i>
                                                <span>{{ $session->device_info['device'] }}</span>
                                            </div>
                                            <small class="text-muted">{{ $session->device_info['browser'] }}</small>
                                        </td>
                                        <td>
                                            <span class="font-monospace text-muted">{{ $session->ip_address }}</span>
                                        </td>
                                        <td>
                                            <div class="text-dark fw-medium font-size-13">{{ $session->time_ago }}</div>
                                            <small class="text-muted">{{ $session->last_seen_date }}</small>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('admin.activities.destroy-session', $session->session_id) }}" method="POST" onsubmit="return confirm('Putus sesi akun ini? Pengguna akan langsung dikeluarkan (logout).')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Putus Sesi (Force Logout)">
                                                    <i class="mdi mdi-logout-variant"></i> Putus Sesi
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PANE 2: Transaksi Penjualan -->
                    <div class="tab-pane" id="tab-sales" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table datatable align-middle table-nowrap table-hover w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu Transaksi</th>
                                        <th>No. Struk</th>
                                        <th>Toko UMKM</th>
                                        <th>Pelanggan</th>
                                        <th>Total Transaksi</th>
                                        <th>Metode Bayar</th>
                                        <th>Jumlah Item</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSales as $sale)
                                    <tr>
                                        <td>
                                            <div class="text-dark fw-medium">{{ $sale->created_at->format('d M Y, H:i') }}</div>
                                            <small class="text-muted">{{ $sale->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <span class="font-monospace fw-bold text-primary">{{ $sale->receipt_number }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark font-size-12 border">
                                                <i class="bx bx-store me-1 text-primary"></i>{{ $sale->store_name ?? 'Default Store' }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $sale->customer_name ?: 'Pelanggan Walk-in' }}
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success font-size-14">
                                                Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(strtolower($sale->payment_method) === 'qris')
                                                <span class="badge bg-danger-subtle text-danger"><i class="mdi mdi-qrcode me-1"></i>QRIS</span>
                                            @elseif(strtolower($sale->payment_method) === 'transfer')
                                                <span class="badge bg-primary-subtle text-primary"><i class="mdi mdi-bank-transfer me-1"></i>Transfer</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success"><i class="mdi mdi-cash me-1"></i>Tunai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary">{{ $sale->items->count() }} item</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PANE 3: Mutasi Stok -->
                    <div class="tab-pane" id="tab-stocks" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table datatable align-middle table-nowrap table-hover w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Toko UMKM</th>
                                        <th>Produk</th>
                                        <th>Tipe Mutasi</th>
                                        <th>Perubahan Qty</th>
                                        <th>Stok Akhir</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentStockMovements as $move)
                                    <tr>
                                        <td>
                                            <div class="text-dark fw-medium">{{ $move->created_at->format('d M Y, H:i') }}</div>
                                            <small class="text-muted">{{ $move->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark font-size-12 border">
                                                {{ $move->store_name ?? 'Default Store' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $move->product->name ?? 'Produk Dihapus' }}</span>
                                        </td>
                                        <td>
                                            @if($move->type === 'in')
                                                <span class="badge bg-success-subtle text-success"><i class="mdi mdi-arrow-down-bold me-1"></i>Barang Masuk</span>
                                            @elseif($move->type === 'out')
                                                <span class="badge bg-danger-subtle text-danger"><i class="mdi mdi-arrow-up-bold me-1"></i>Barang Keluar (POS)</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning"><i class="mdi mdi-tune me-1"></i>Penyesuaian</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ $move->qty_change > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $move->qty_change > 0 ? '+' : '' }}{{ $move->qty_change }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary">{{ $move->qty_after }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $move->reference ? '[' . $move->reference . '] ' : '' }}{{ $move->notes }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PANE 4: Audit Log Sistem -->
                    <div class="tab-pane" id="tab-audit" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table datatable align-middle table-nowrap table-hover w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu Log</th>
                                        <th>Pengguna</th>
                                        <th>Toko</th>
                                        <th>Aksi</th>
                                        <th>Deskripsi Log</th>
                                        <th>Alamat IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($auditLogs as $log)
                                    <tr>
                                        <td>
                                            <div class="text-dark fw-medium">{{ $log->created_at->format('d M Y, H:i:s') }}</div>
                                            <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $log->user->name ?? 'System' }}</div>
                                            <small class="text-muted">{{ $log->user->email ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $log->store->name ?? 'SaaS Pusat' }}</span>
                                        </td>
                                        <td>
                                            @if($log->action === 'login')
                                                <span class="badge bg-success-subtle text-success"><i class="bx bx-log-in me-1"></i>Login</span>
                                            @elseif($log->action === 'logout')
                                                <span class="badge bg-secondary-subtle text-secondary"><i class="bx bx-log-out me-1"></i>Logout</span>
                                            @elseif($log->action === 'sale_created')
                                                <span class="badge bg-primary-subtle text-primary"><i class="bx bx-cart-alt me-1"></i>Penjualan</span>
                                            @else
                                                <span class="badge bg-info-subtle text-info">{{ $log->action }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $log->description }}
                                        </td>
                                        <td>
                                            <span class="font-monospace text-muted">{{ $log->ip_address ?: '-' }}</span>
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
</div>
@endsection
