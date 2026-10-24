@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                <i class="mdi mdi-robot-outline text-primary me-2"></i> Asisten AI UMKM
            </h4>
        </div>
        <p class="text-muted">Analisis pintar berdasarkan kecepatan penjualan 30 hari terakhir untuk memandu keputusan bisnis Anda.</p>
    </div>
</div>

<div class="row">
    <!-- Restock Prediction Column -->
    <div class="col-xl-7">
        <div class="card card-h-100">
            <div class="card-header align-items-center d-flex border-bottom-0 pb-0">
                <h4 class="card-title mb-0 flex-grow-1">
                    <i class="mdi mdi-alert-octagram text-warning me-1"></i> Prediksi Stok Habis (Dalam 7 Hari)
                </h4>
            </div>
            <div class="card-body">
                @if(count($restockRecommendations) > 0)
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Sisa Stok</th>
                                <th>Kecepatan Jual</th>
                                <th>Prediksi Habis</th>
                                <th>Saran Kulak</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($restockRecommendations as $rec)
                            <tr>
                                <td>
                                    <h5 class="font-size-14 mb-1">{{ $rec['product']->name }}</h5>
                                    <p class="text-muted mb-0 font-size-12">{{ $rec['product']->product_code }}</p>
                                </td>
                                <td>
                                    <span class="badge {{ $rec['product']->stock <= $rec['product']->min_stock ? 'bg-danger' : 'bg-warning' }} font-size-12">
                                        {{ $rec['product']->stock }}
                                    </span>
                                </td>
                                <td>{{ $rec['velocity'] }} /hari</td>
                                <td>
                                    @if($rec['days_remaining'] == 0)
                                        <strong class="text-danger">Hari ini / Habis</strong>
                                    @else
                                        <strong class="text-warning">{{ $rec['days_remaining'] }} Hari lagi</strong>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary font-size-13 px-2 py-1">
                                        +{{ $rec['suggested_restock'] }} pcs
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-soft-primary">
                                        <i class="mdi mdi-cart-plus"></i> Kulak
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="avatar-md mx-auto mb-4">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle font-size-24">
                            <i class="mdi mdi-check-all"></i>
                        </div>
                    </div>
                    <h5>Aman Terkendali!</h5>
                    <p class="text-muted">Tidak ada produk laris yang terancam habis dalam 7 hari ke depan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Margin Leakage Column -->
    <div class="col-xl-5">
        <div class="card card-h-100">
            <div class="card-header align-items-center d-flex border-bottom-0 pb-0">
                <h4 class="card-title mb-0 flex-grow-1">
                    <i class="mdi mdi-trending-down text-danger me-1"></i> Peringatan Margin (Kebocoran Laba)
                </h4>
            </div>
            <div class="card-body">
                @if(count($criticalMargins) > 0)
                
                @if($totalLosses > 0)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-block-helper me-2"></i>
                    Ada <strong>{{ $totalLosses }} produk</strong> yang dijual lebih murah atau sama dengan modal beli (RUGI)!
                </div>
                @endif

                <div class="table-responsive mt-3">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>HPP (Beli)</th>
                                <th>Jual</th>
                                <th>Status Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criticalMargins as $margin)
                            <tr>
                                <td>
                                    <h5 class="font-size-14 mb-1">{{ $margin['product']->name }}</h5>
                                </td>
                                <td>Rp {{ number_format($margin['product']->purchase_price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($margin['product']->selling_price, 0, ',', '.') }}</td>
                                <td>
                                    @if($margin['status'] == 'Rugi')
                                        <span class="badge bg-danger">RUGI ({{ $margin['margin_percent'] }}%)</span>
                                    @else
                                        <span class="badge bg-warning">MEPET ({{ $margin['margin_percent'] }}%)</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">Laba: Rp {{ number_format($margin['margin'], 0, ',', '.') }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('products.index') }}" class="btn btn-primary w-100">
                        <i class="mdi mdi-pencil"></i> Revisi Harga Sekarang
                    </a>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="avatar-md mx-auto mb-4">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle font-size-24">
                            <i class="mdi mdi-cash-multiple"></i>
                        </div>
                    </div>
                    <h5>Laba Sehat!</h5>
                    <p class="text-muted">Tidak ada produk dengan margin minus atau di bawah 10%.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
