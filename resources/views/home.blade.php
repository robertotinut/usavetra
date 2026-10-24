@extends('layouts.app')
@section('title', 'Dashboard Bisnis')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard Bisnis</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Usavetra</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@if(isset($hasCustomItems) && $hasCustomItems)
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-outline me-2"></i>
            <strong>Peringatan Laporan!</strong> Anda memiliki transaksi menggunakan "Item Manual" hari ini. Hal ini dapat menyebabkan laporan keuangan dan laba kotor Anda menjadi tidak akurat karena HPP (Harga Pokok Penjualan) item manual bernilai Rp 0. Kami sarankan Anda segera <a href="{{ route('pending-products.index') }}" class="alert-link">mendaftarkan produk tersebut ke Master Data</a>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

<div class="row">
    <!-- Card Omzet -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Omzet Hari Ini</span>
                        <h4 class="mb-3">
                            Rp <span class="counter-value" data-target="{{ $omzetHariIni }}">0</span>
                        </h4>
                    </div>
                    <div class="col-4">
                        <i class="bx bx-wallet font-size-24 text-primary float-end"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Laba -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Est. Laba Hari Ini</span>
                        <h4 class="mb-3">
                            Rp <span class="counter-value" data-target="{{ $labaHariIni }}">0</span>
                        </h4>
                    </div>
                    <div class="col-4">
                        <i class="bx bx-trending-up font-size-24 text-success float-end"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Total Transaksi -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Transaksi Penjualan</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="{{ $totalTransaksi }}">0</span> Nota
                        </h4>
                    </div>
                    <div class="col-4">
                        <i class="bx bx-cart font-size-24 text-info float-end"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Pengeluaran Kulakan -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Pengeluaran Kulakan</span>
                        <h4 class="mb-3">
                            Rp <span class="counter-value" data-target="{{ $pengeluaranHariIni }}">0</span>
                        </h4>
                    </div>
                    <div class="col-4">
                        <i class="bx bx-package font-size-24 text-danger float-end"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @if(isset($stokMinus) && $stokMinus->count() > 0)
    <div class="col-12 mb-4">
        <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center" role="alert">
            <i class="mdi mdi-alert-circle text-danger font-size-24 me-3"></i>
            <div>
                <h5 class="text-danger mb-1">Peringatan: Ada Stok Minus! (Butuh Disesuaikan)</h5>
                <p class="mb-0">
                    Terdapat <strong>{{ $stokMinus->count() }}</strong> produk yang stoknya minus (penjualan melebihi stok yang tercatat). 
                    Silakan klik tombol <strong>Sesuaikan Stok</strong> atau lakukan kulakan pada barang tersebut agar data inventori akurat.
                </p>
            </div>
            <div class="ms-auto">
                <!-- Arahkan ke halaman edit produk / kulakan -->
                <a href="{{ route('products.index') }}" class="btn btn-danger btn-sm rounded-pill px-3">Lihat Produk Minus</a>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabel Stok Kritis -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Peringatan Stok Tipis</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('products.index') }}" class="btn btn-soft-primary btn-sm">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Stok Saat Ini</th>
                                <th>Batas Min</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stokKritis as $item)
                            <tr>
                                <td>
                                    <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">{{ $item->name }}</a></h5>
                                    <p class="text-muted mb-0 font-size-13">{{ $item->barcode ?? '-' }}</p>
                                </td>
                                <td>
                                    <span class="badge bg-danger">Sisa {{ $item->stock }}</span>
                                </td>
                                <td>{{ $item->min_stock }}</td>
                                <td>
                                    <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-soft-primary">Kulak Sekarang</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">Stok masih dalam kondisi aman.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Panduan Cepat -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Jalan Pintas</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <a href="{{ route('sales.create') }}" class="btn btn-soft-primary w-100 p-3">
                            <i class="bx bx-cart font-size-24 d-block mb-2"></i>
                            <span class="fw-medium">Buka Kasir (POS)</span>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ route('purchases.create') }}" class="btn btn-soft-info w-100 p-3">
                            <i class="bx bx-package font-size-24 d-block mb-2"></i>
                            <span class="fw-medium">Input Kulakan</span>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ route('products.index') }}" class="btn btn-soft-success w-100 p-3">
                            <i class="bx bx-box font-size-24 d-block mb-2"></i>
                            <span class="fw-medium">Kelola Produk</span>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ route('categories.index') }}" class="btn btn-soft-warning w-100 p-3">
                            <i class="bx bx-grid-alt font-size-24 d-block mb-2"></i>
                            <span class="fw-medium">Kategori Barang</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
