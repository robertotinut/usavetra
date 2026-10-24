@extends('layouts.app')

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

<!-- Alert Notifikasi -->
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

<!-- Metric KPI Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-start border-4 border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-2 d-block text-truncate fw-medium">Sedang Online Sekarang</span>
                        <h3 class="mb-0 text-success d-flex align-items-center gap-2">
                            <span>{{ $onlineUsersCount }}</span>
                            <span class="badge bg-success-subtle text-success font-size-12 fw-normal">
                                <span class="spinner-grow spinner-grow-sm text-success me-1" role="status" style="width: 8px; height: 8px;"></span> Live
                            </span>
                        </h3>
                        <small class="text-muted">Aktif dalam 15 menit terakhir</small>
                    </div>
                    <div class="flex-shrink-0 text-end">
                        <div class="avatar-sm rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="mdi mdi-account-clock font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-start border-4 border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-2 d-block text-truncate fw-medium">Toko Aktif Hari Ini</span>
                        <h3 class="mb-0 text-info">
                            {{ $activeStoresToday }} <span class="font-size-14 text-muted fw-normal">/ {{ $totalStoresCount }} Toko</span>
                        </h3>
                        <small class="text-muted">Toko yang mencatat transaksi hari ini</small>
                    </div>
                    <div class="flex-shrink-0 text-end">
                        <div class="avatar-sm rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="mdi mdi-store-check font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-start border-4 border-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-2 d-block text-truncate fw-medium">Transaksi Hari Ini</span>
                        <h3 class="mb-0 text-primary">{{ $todaySalesCount }}</h3>
                        <small class="text-primary fw-semibold">Rp {{ number_format($todaySalesSum, 0, ',', '.') }}</small>
                    </div>
                    <div class="flex-shrink-0 text-end">
                        <div class="avatar-sm rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="mdi mdi-receipt-text-check font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100 border-start border-4 border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-2 d-block text-truncate fw-medium">Total Akun Terdaftar</span>
                        <h3 class="mb-0 text-warning">{{ $totalUsersCount }}</h3>
                        <small class="text-muted">Super Admin, Owner & Kasir</small>
                    </div>
                    <div class="flex-shrink-0 text-end">
                        <div class="avatar-sm rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="mdi mdi-account-group font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search Bar -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('admin.activities.index') }}" class="row g-2 align-items-center">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    
                    <div class="col-md-4">
                        <label class="form-label font-size-12 mb-1 text-muted">Filter Toko / UMKM</label>
                        <select name="store_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Toko / UMKM --</option>
                            @foreach($stores as $st)
                                <option value="{{ $st->id }}" {{ $selectedStoreId == $st->id ? 'selected' : '' }}>
                                    {{ $st->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label font-size-12 mb-1 text-muted">Pencarian (Nama, Email, IP, No. Struk)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Ketik kata kunci pencarian..." value="{{ $search }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="mdi mdi-magnify me-1"></i> Cari
                            </button>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-1 mt-auto">
                        @if($selectedStoreId || $search)
                            <a href="{{ route('admin.activities.index', ['tab' => $activeTab]) }}" class="btn btn-sm btn-outline-secondary w-50">
                                <i class="mdi mdi-refresh"></i> Reset
                            </a>
                        @endif
                        <button type="button" class="btn btn-sm btn-outline-primary {{ ($selectedStoreId || $search) ? 'w-50' : 'w-100' }}" onclick="window.location.reload()">
                            <i class="mdi mdi-sync me-1"></i> Refresh Live
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Konten Tab Utama -->
<div class="row">
    <div class="col-xl-9 col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-bottom">
                <ul class="nav nav-pills card-header-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'sessions' ? 'active' : '' }}" href="{{ route('admin.activities.index', ['tab' => 'sessions', 'store_id' => $selectedStoreId, 'search' => $search]) }}">
                            <i class="mdi mdi-account-clock-outline me-1"></i> Sesi & Online ({{ $sessions->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'sales' ? 'active' : '' }}" href="{{ route('admin.activities.index', ['tab' => 'sales', 'store_id' => $selectedStoreId, 'search' => $search]) }}">
                            <i class="mdi mdi-cash-register me-1"></i> Transaksi Penjualan ({{ $recentSales->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'stocks' ? 'active' : '' }}" href="{{ route('admin.activities.index', ['tab' => 'stocks', 'store_id' => $selectedStoreId, 'search' => $search]) }}">
                            <i class="mdi mdi-swap-vertical me-1"></i> Pergerakan Stok ({{ $recentStockMovements->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'audit' ? 'active' : '' }}" href="{{ route('admin.activities.index', ['tab' => 'audit', 'store_id' => $selectedStoreId, 'search' => $search]) }}">
                            <i class="mdi mdi-shield-account-outline me-1"></i> Audit Log Sistem ({{ $auditLogs->count() }})
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-0">
                <!-- TAB 1: Sesi Pengguna & Online -->
                @if($activeTab === 'sessions')
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pengguna</th>
                                <th>Toko / UMKM</th>
                                <th>Status</th>
                                <th>Perangkat & Browser</th>
                                <th>Alamat IP</th>
                                <th>Aktivitas Terakhir</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sessions as $session)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            {{ strtoupper(substr($session->user_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $session->user_name ?? 'Tamu / Tanpa Nama' }}</div>
                                            <small class="text-muted">{{ $session->user_email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $session->store_name ?? 'SaaS Pusat' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $session->badge_class }}">
                                        @if($session->status === 'online')
                                            <span class="spinner-grow spinner-grow-sm me-1" role="status" style="width: 6px; height: 6px;"></span>
                                        @endif
                                        {{ $session->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1 font-size-13">
                                        <i class="mdi {{ $session->device_info['icon'] }} font-size-16 text-primary"></i>
                                        <span>{{ $session->device_info['device'] }}</span>
                                    </div>
                                    <small class="text-muted font-size-11">{{ $session->device_info['browser'] }}</small>
                                </td>
                                <td>
                                    <code>{{ $session->ip_address }}</code>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $session->time_ago }}</div>
                                    <small class="text-muted">{{ $session->last_seen_date }}</small>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.activities.destroy-session', $session->session_id) }}" method="POST" onsubmit="return confirm('Putus sesi ini secara paksa? Pengguna akan diminta login kembali.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Putus Sesi (Force Logout)">
                                            <i class="mdi mdi-logout-variant"></i> Putus Sesi
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="mdi mdi-account-off-outline font-size-36 d-block mb-2"></i>
                                    Tidak ada data sesi aktif yang ditemukan sesuai filter.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- TAB 2: Live Transaksi Penjualan -->
                @if($activeTab === 'sales')
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Struk</th>
                                <th>Toko UMKM</th>
                                <th>Pelanggan</th>
                                <th>Nominal</th>
                                <th>Metode Bayar</th>
                                <th>Waktu Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary font-monospace">{{ $sale->receipt_number }}</span>
                                    <div class="small text-muted">{{ $sale->items->count() }} jenis barang</div>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info fw-semibold font-size-12">
                                        <i class="mdi mdi-store me-1"></i>{{ $sale->store_name ?? 'Default Store' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $sale->customer_name ?: 'Pelanggan Umum' }}
                                </td>
                                <td>
                                    <div class="fw-bold text-success font-size-14">
                                        Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    @if(strtolower($sale->payment_method) === 'qris')
                                        <span class="badge bg-danger-subtle text-danger"><i class="mdi mdi-qrcode-scan me-1"></i>QRIS</span>
                                    @elseif(strtolower($sale->payment_method) === 'transfer')
                                        <span class="badge bg-primary-subtle text-primary"><i class="mdi mdi-bank-transfer me-1"></i>Transfer</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success"><i class="mdi mdi-cash me-1"></i>Tunai</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $sale->created_at->diffForHumans() }}</div>
                                    <small class="text-muted">{{ $sale->created_at->format('d M Y, H:i') }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="mdi mdi-receipt-text-outline font-size-36 d-block mb-2"></i>
                                    Belum ada catatan transaksi penjualan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- TAB 3: Pergerakan Stok -->
                @if($activeTab === 'stocks')
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Toko UMKM</th>
                                <th>Produk</th>
                                <th>Jenis Aktivitas</th>
                                <th>Perubahan Qty</th>
                                <th>Sisa Stok</th>
                                <th>Keterangan / Ref</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentStockMovements as $move)
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $move->store_name ?? 'Default Store' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $move->product->name ?? 'Produk Dihapus' }}</span>
                                </td>
                                <td>
                                    @if($move->type === 'in')
                                        <span class="badge bg-success"><i class="mdi mdi-arrow-down me-1"></i>Barang Masuk / Restock</span>
                                    @elseif($move->type === 'out')
                                        <span class="badge bg-danger"><i class="mdi mdi-arrow-up me-1"></i>Barang Keluar (POS)</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="mdi mdi-tune me-1"></i>Penyesuaian (Adj)</span>
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
                                <td>
                                    <small class="text-muted">{{ $move->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada log pergerakan stok.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- TAB 4: Audit Log Sistem -->
                @if($activeTab === 'audit')
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Waktu</th>
                                <th>Pengguna</th>
                                <th>Toko</th>
                                <th>Aksi</th>
                                <th>Deskripsi Aktivitas</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditLogs as $log)
                            <tr>
                                <td>
                                    <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                                    <div class="small fw-semibold">{{ $log->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $log->user->name ?? 'System / Guest' }}</div>
                                    <small class="text-muted">{{ $log->user->email ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $log->store->name ?? 'Pusat' }}</span>
                                </td>
                                <td>
                                    @if($log->action === 'login')
                                        <span class="badge bg-success-subtle text-success"><i class="mdi mdi-login me-1"></i>Login</span>
                                    @elseif($log->action === 'logout')
                                        <span class="badge bg-secondary-subtle text-secondary"><i class="mdi mdi-logout me-1"></i>Logout</span>
                                    @elseif($log->action === 'sale_created')
                                        <span class="badge bg-primary-subtle text-primary"><i class="mdi mdi-cart-check me-1"></i>Penjualan</span>
                                    @else
                                        <span class="badge bg-info-subtle text-info">{{ $log->action }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $log->description }}
                                </td>
                                <td>
                                    <code>{{ $log->ip_address ?: '-' }}</code>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada audit log sistem tercatat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Toko Paling Aktif (Leaderboard) -->
    <div class="col-xl-3 col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0 font-size-15 d-flex align-items-center">
                    <i class="mdi mdi-trophy-variant text-warning me-2 font-size-18"></i> Toko Paling Aktif
                </h5>
            </div>
            <div class="card-body p-3">
                <p class="text-muted font-size-12 mb-3">Toko UMKM dengan volume transaksi tertinggi di platform Usavetra:</p>
                <div class="d-flex flex-column gap-3">
                    @foreach($topStores as $index => $storeRank)
                    <div class="d-flex align-items-center p-2 rounded border bg-light">
                        <div class="avatar-xs rounded-circle {{ $index === 0 ? 'bg-warning text-dark' : 'bg-primary-subtle text-primary' }} fw-bold d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                            #{{ $index + 1 }}
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="text-truncate mb-0 font-size-13">{{ $storeRank->name }}</h6>
                            <small class="text-muted font-size-11">{{ $storeRank->sales_count }} transaksi</small>
                        </div>
                        <div class="text-end ps-2">
                            <span class="text-success fw-bold font-size-12 d-block">
                                Rp {{ number_format($storeRank->sales_sum_total_amount ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card bg-primary-subtle text-primary">
            <div class="card-body p-3">
                <h6 class="text-primary font-size-14 mb-2"><i class="mdi mdi-information-outline me-1"></i> Tips Super Admin</h6>
                <p class="font-size-12 mb-0" style="color: #495057;">
                    Sesi online dihitung otomatis dari aktivitas interaksi user di web dalam rentang 15 menit. Anda dapat memutuskan sesi pengguna yang tidak dikenal melalui tombol <strong>Putus Sesi</strong>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
