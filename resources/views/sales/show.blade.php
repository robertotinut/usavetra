@extends('layouts.app')
@section('title', 'Detail Transaksi ' . $sale->receipt_number)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Detail Transaksi</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Riwayat Transaksi</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <div>
                        <h4 class="card-title mb-1">Nota Penjualan</h4>
                        <p class="text-muted mb-0">No: <strong>{{ $sale->receipt_number }}</strong></p>
                    </div>
                    <div class="text-end">
                        <p class="text-muted mb-1">Tanggal Transaksi</p>
                        <h5 class="font-size-15 mb-0">{{ $sale->created_at->format('d M Y - H:i') }}</h5>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="text-muted">
                            <h5 class="font-size-15 mb-2">Data Pelanggan:</h5>
                            <p class="mb-1">{{ $sale->customer_name ?: 'Pelanggan Umum (Walk-in)' }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <div class="text-muted">
                            <h5 class="font-size-15 mb-2">Kasir Bertugas:</h5>
                            <p class="mb-1">{{ $sale->user->name ?? 'Kasir' }}</p>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No.</th>
                                <th>Item / Produk</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <h5 class="font-size-14 text-truncate mb-1">
                                        {{ $item->product ? $item->product->name : $item->custom_name }}
                                    </h5>
                                    <p class="text-muted mb-0 font-size-12">
                                        @if($item->product)
                                            <span class="badge bg-success bg-soft text-success">Master</span> {{ $item->product->product_code }}
                                        @else
                                            <span class="badge bg-warning bg-soft text-warning">Manual Item</span>
                                        @endif
                                    </p>
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            
                            <tr>
                                <td colspan="4" class="border-0 text-end"><strong>Total Belanja</strong></td>
                                <td class="border-0 text-end"><h4 class="m-0 text-primary">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</h4></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="border-0 text-end">Dibayar ({{ $sale->payment_method }})</td>
                                <td class="border-0 text-end">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="border-0 text-end">Kembalian</td>
                                <td class="border-0 text-end">Rp {{ number_format($sale->return_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-print-none mt-4 text-center">
                    <a href="{{ route('sales.index') }}" class="btn btn-light w-md me-2">Kembali</a>
                    <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="btn btn-primary w-md"><i class="mdi mdi-printer"></i> Cetak Struk</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
