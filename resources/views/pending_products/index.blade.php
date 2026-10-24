@extends('layouts.app')
@section('title', 'Daftar Item Tertunda')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Daftar Item Tertunda (Item Manual)</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Item Tertunda</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <p class="text-muted">Item di bawah ini adalah barang yang pernah ditambahkan secara manual di Kasir namun belum terdaftar di Master Data. Laba kotor dari item-item ini tidak akurat karena HPP-nya 0. Harap segera jadikan master data.</p>
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-check">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Item Manual</th>
                                <th>Harga Jual Terakhir</th>
                                <th>Total Terjual</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingItems as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><strong>{{ $item->custom_name }}</strong></td>
                                <td>Rp {{ number_format($item->max_price, 0, ',', '.') }}</td>
                                <td>{{ $item->total_sold }} transaksi</td>
                                <td>
                                    <a href="{{ route('pending-products.create', ['name' => $item->custom_name, 'price' => $item->max_price]) }}" class="btn btn-sm btn-primary">
                                        <i class="mdi mdi-plus-circle-outline"></i> Buat Master Data
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <h5 class="text-muted mb-0">Hore! Tidak ada item manual yang tertunda.</h5>
                                    <p class="text-muted mb-0">Semua laporan HPP dan Laba Anda akurat.</p>
                                </td>
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
