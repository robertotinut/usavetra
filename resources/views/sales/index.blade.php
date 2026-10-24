@extends('layouts.app')
@section('title', 'Riwayat Penjualan (Kasir)')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Riwayat Penjualan</h4>
            <div class="page-title-right">
                <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm"><i class="mdi mdi-shopping me-1"></i> Buka Kasir (POS)</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table datatable align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Struk</th>
                                <th>Pelanggan</th>
                                <th>Total Belanja</th>
                                <th>Metode Bayar</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                            <tr>
                                <td>{{ date('d M Y', strtotime($sale->date)) }}</td>
                                <td><span class="text-body fw-bold">{{ $sale->receipt_number }}</span></td>
                                <td>{{ $sale->customer_name ?? 'Umum / Walk-in' }}</td>
                                <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($sale->payment_method == 'Tunai')
                                        <span class="badge bg-success">Tunai</span>
                                    @else
                                        <span class="badge bg-info">{{ $sale->payment_method }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info" title="Lihat Detail"><i class="mdi mdi-eye"></i></a>
                                    <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="btn btn-sm btn-secondary" title="Cetak Ulang Struk"><i class="mdi mdi-printer"></i> Cetak</a>
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
@endsection
