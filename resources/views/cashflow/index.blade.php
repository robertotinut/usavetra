@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><i class="mdi mdi-wallet-outline text-primary me-2"></i> Buku Kas Harian</h4>
        </div>
    </div>
</div>

{{-- Ringkasan --}}
<div class="row">
    <div class="col-xl-4 col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-1">Total Uang Masuk</p>
                        <h5 class="mb-0 text-success">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h5>
                    </div>
                    <div class="avatar-sm align-self-center">
                        <span class="avatar-title rounded-circle bg-success-subtle text-success font-size-24"><i class="mdi mdi-arrow-down-bold"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-1">Total Uang Keluar</p>
                        <h5 class="mb-0 text-danger">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h5>
                    </div>
                    <div class="avatar-sm align-self-center">
                        <span class="avatar-title rounded-circle bg-danger-subtle text-danger font-size-24"><i class="mdi mdi-arrow-up-bold"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-1">Saldo Kas</p>
                        <h5 class="mb-0 {{ $saldo >= 0 ? 'text-primary' : 'text-danger' }}">Rp {{ number_format($saldo, 0, ',', '.') }}</h5>
                    </div>
                    <div class="avatar-sm align-self-center">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-24"><i class="mdi mdi-cash-register"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Riwayat Kas</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahKas">
                    <i class="mdi mdi-plus me-1"></i> Catat Kas Baru
                </button>
            </div>
            <div class="card-body">
                {{-- Filter Tanggal --}}
                <form method="GET" class="row g-2 mb-4">
                    <div class="col-md-4">
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-soft-primary w-100"><i class="mdi mdi-filter me-1"></i> Filter</button>
                    </div>
                </form>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table datatable align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Keterangan</th>
                                <th>Tipe</th>
                                <th>Nominal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cashflows as $cf)
                            <tr>
                                <td>{{ $cf->date->format('d/m/Y') }}</td>
                                <td><span class="badge bg-light text-dark">{{ $cf->category }}</span></td>
                                <td>{{ $cf->description ?? '-' }}</td>
                                <td>
                                    @if($cf->type == 'in')
                                        <span class="badge bg-success">Masuk</span>
                                    @else
                                        <span class="badge bg-danger">Keluar</span>
                                    @endif
                                </td>
                                <td class="fw-bold {{ $cf->type == 'in' ? 'text-success' : 'text-danger' }}">
                                    {{ $cf->type == 'in' ? '+' : '-' }} Rp {{ number_format($cf->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    <form action="{{ route('cashflow.destroy', $cf->id) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-soft-danger"><i class="mdi mdi-delete"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar Kategori Cepat --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Panduan Kategori</h5></div>
            <div class="card-body">
                <p class="text-muted font-size-13">Gunakan nama kategori berikut agar laporan Anda rapi:</p>
                <div class="mb-2"><span class="badge bg-success-subtle text-success me-1">Masuk</span> Modal / Setor Tunai</div>
                <div class="mb-2"><span class="badge bg-success-subtle text-success me-1">Masuk</span> Pinjaman Diterima</div>
                <div class="mb-2"><span class="badge bg-success-subtle text-success me-1">Masuk</span> Pendapatan Lain</div>
                <hr>
                <div class="mb-2"><span class="badge bg-danger-subtle text-danger me-1">Keluar</span> Bayar Listrik</div>
                <div class="mb-2"><span class="badge bg-danger-subtle text-danger me-1">Keluar</span> Bayar Sewa Toko</div>
                <div class="mb-2"><span class="badge bg-danger-subtle text-danger me-1">Keluar</span> Gaji Karyawan</div>
                <div class="mb-2"><span class="badge bg-danger-subtle text-danger me-1">Keluar</span> Belanja Operasional</div>
                <div class="mb-2"><span class="badge bg-danger-subtle text-danger me-1">Keluar</span> Tarik Tunai Pribadi</div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="tambahKas" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('cashflow.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Catat Kas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipe Transaksi</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type_in" value="in" checked>
                                <label class="form-check-label text-success fw-bold" for="type_in">💰 Uang Masuk</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type_out" value="out">
                                <label class="form-check-label text-danger fw-bold" for="type_out">💸 Uang Keluar</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="category" class="form-control" placeholder="Misal: Bayar Listrik, Modal, Gaji" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nominal</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount" class="form-control" placeholder="0" required min="1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan <span class="text-muted">(Opsional)</span></label>
                        <input type="text" name="description" class="form-control" placeholder="Catatan tambahan...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
