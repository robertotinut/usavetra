@extends('layouts.app')
@section('title', 'Riwayat Kulakan')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Riwayat Kulakan</h4>
            <div class="page-title-right">
                <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm"><i class="mdi mdi-plus me-1"></i> Input Kulakan Baru</a>
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
                                <th>No. Invoice</th>
                                <th>Supplier</th>
                                <th>Total Bayar</th>
                                <th>Catatan</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $purchase)
                            <tr>
                                <td>{{ date('d M Y', strtotime($purchase->date)) }}</td>
                                <td><span class="text-body fw-bold">{{ $purchase->invoice_number }}</span></td>
                                <td>{{ $purchase->supplier->name ?? 'Tanpa Supplier' }}</td>
                                <td>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                <td>{{ Str::limit($purchase->notes, 30) ?? '-' }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="mdi mdi-eye"></i> Detail
                                        </a>
                                        <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MEMBATALKAN transaksi kulakan ini? Stok produk akan dikurangi kembali.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Batal Kulakan">
                                                <i class="mdi mdi-close-circle"></i> Batal
                                            </button>
                                        </form>
                                    </div>
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
