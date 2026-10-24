@extends('layouts.app')
@section('title', 'Detail Kulakan ' . $purchase->invoice_number)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Detail Kulakan</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Riwayat Kulakan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="invoice-title">
                    <h4 class="float-end font-size-16">Invoice #{{ $purchase->invoice_number }}</h4>
                    <div class="mb-4">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo" height="20"/>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <address>
                            <strong>Informasi Supplier:</strong><br>
                            {{ $purchase->supplier->name ?? 'Tanpa Supplier' }}<br>
                            {{ $purchase->supplier->phone ?? '' }}<br>
                            {{ $purchase->supplier->address ?? '' }}
                        </address>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <address class="mt-2 mt-sm-0">
                            <strong>Tanggal Pembelian:</strong><br>
                            {{ date('d F Y', strtotime($purchase->date)) }}<br><br>
                        </address>
                    </div>
                </div>
                
                @if($purchase->notes)
                <div class="row">
                    <div class="col-12">
                        <address>
                            <strong>Catatan:</strong><br>
                            {{ $purchase->notes }}
                        </address>
                    </div>
                </div>
                @endif
                
                <div class="py-2 mt-3">
                    <h3 class="font-size-15 fw-bold">Rincian Barang</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 70px;">No.</th>
                                <th>Item</th>
                                <th>Harga Beli</th>
                                <th>Kuantitas</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total Bayar</strong></td>
                                <td class="text-end"><h4 class="m-0 text-primary">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</h4></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-print-none mt-4 text-center">
                    <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1"><i class="fa fa-print"></i> Cetak</a>
                    <a href="{{ route('purchases.index') }}" class="btn btn-primary waves-effect waves-light">Kembali ke Riwayat</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
