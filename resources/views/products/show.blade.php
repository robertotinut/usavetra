@extends('layouts.app')
@section('title', 'Kartu Stok: ' . $product->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Kartu Stok: {{ $product->name }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Master Produk</a></li>
                    <li class="breadcrumb-item active">Kartu Stok</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-sm-6">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Stok Saat Ini</p>
                        <h4 class="mb-0">{{ $product->stock }}</h4>
                    </div>
                    <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                        <span class="avatar-title">
                            <i class="bx bx-package font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title m-0">Riwayat Pergerakan Stok</h4>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm"><i class="bx bx-arrow-back"></i> Kembali</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Tanggal & Waktu</th>
                                <th>Tipe</th>
                                <th>Referensi</th>
                                <th>Keterangan</th>
                                <th class="text-center">Awal</th>
                                <th class="text-center">In / Out / Adj</th>
                                <th class="text-center">Sisa (Balance)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $movement)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $movement->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    @if($movement->type == 'in')
                                        <span class="badge bg-success"><i class="bx bx-down-arrow-alt"></i> Masuk (IN)</span>
                                    @elseif($movement->type == 'out')
                                        <span class="badge bg-danger"><i class="bx bx-up-arrow-alt"></i> Keluar (OUT)</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="bx bx-pencil"></i> Penyesuaian</span>
                                    @endif
                                </td>
                                <td><span class="fw-bold">{{ $movement->reference }}</span></td>
                                <td>{{ $movement->notes ?? '-' }}</td>
                                <td class="text-center">{{ $movement->qty_before }}</td>
                                <td class="text-center">
                                    @if($movement->qty_change > 0)
                                        <span class="text-success fw-bold">+{{ $movement->qty_change }}</span>
                                    @elseif($movement->qty_change < 0)
                                        <span class="text-danger fw-bold">{{ $movement->qty_change }}</span>
                                    @else
                                        <span class="text-muted fw-bold">0</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $movement->qty_after }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada riwayat pergerakan stok untuk produk ini. Transaksi/stok masa lalu tidak dilacak, pencatatan baru dimulai sekarang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(!isset($hasPremium) || !$hasPremium)
                    <div class="alert alert-warning text-center mt-3 mb-0">
                        <i class="mdi mdi-lock-outline mdi-24px d-block mb-1 text-warning"></i>
                        <strong>Riwayat Dibatasi!</strong> Pengguna Gratis hanya dapat melihat 3 riwayat stok terakhir. 
                        Silakan <strong>Upgrade Premium</strong> untuk melakukan audit stok tanpa batas.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
