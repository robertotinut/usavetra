@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><i class="mdi mdi-notebook-outline text-primary me-2"></i> Hutang & Piutang</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahDebt">
                <i class="mdi mdi-plus me-1"></i> Catat Baru
            </button>
        </div>
    </div>
</div>

{{-- Ringkasan --}}
<div class="row">
    <div class="col-xl-6 col-md-6">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-1">Total Piutang Belum Lunas</p>
                        <p class="text-muted font-size-12 mb-1">(Orang lain hutang ke Anda)</p>
                        <h5 class="mb-0 text-success">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h5>
                    </div>
                    <div class="avatar-sm align-self-center">
                        <span class="avatar-title rounded-circle bg-success-subtle text-success font-size-24"><i class="mdi mdi-account-arrow-left"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-1">Total Hutang Belum Lunas</p>
                        <p class="text-muted font-size-12 mb-1">(Anda hutang ke orang lain)</p>
                        <h5 class="mb-0 text-danger">Rp {{ number_format($totalHutang, 0, ',', '.') }}</h5>
                    </div>
                    <div class="avatar-sm align-self-center">
                        <span class="avatar-title rounded-circle bg-danger-subtle text-danger font-size-24"><i class="mdi mdi-account-arrow-right"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tab Piutang / Hutang --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <ul class="nav nav-tabs nav-tabs-custom mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $type == 'receivable' ? 'active' : '' }}" href="{{ route('debts.index', ['type' => 'receivable']) }}">
                            <i class="mdi mdi-account-arrow-left me-1"></i> Piutang <span class="badge bg-success ms-1">Orang hutang ke saya</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $type == 'payable' ? 'active' : '' }}" href="{{ route('debts.index', ['type' => 'payable']) }}">
                            <i class="mdi mdi-account-arrow-right me-1"></i> Hutang <span class="badge bg-danger ms-1">Saya hutang ke orang</span>
                        </a>
                    </li>
                </ul>

                <div class="table-responsive">
                    <table class="table datatable align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>No HP</th>
                                <th>Total</th>
                                <th>Sudah Bayar</th>
                                <th>Sisa</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($debts as $debt)
                            <tr>
                                <td><strong>{{ $debt->person_name }}</strong></td>
                                <td>{{ $debt->phone ?? '-' }}</td>
                                <td>Rp {{ number_format($debt->total_amount, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($debt->paid_amount, 0, ',', '.') }}</td>
                                <td class="fw-bold {{ $debt->remaining > 0 ? 'text-danger' : 'text-success' }}">
                                    Rp {{ number_format($debt->remaining, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($debt->due_date)
                                        <span class="{{ $debt->due_date->isPast() && $debt->status != 'paid' ? 'text-danger fw-bold' : '' }}">
                                            {{ $debt->due_date->format('d/m/Y') }}
                                            @if($debt->due_date->isPast() && $debt->status != 'paid')
                                                <i class="mdi mdi-alert text-danger"></i>
                                            @endif
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($debt->status == 'paid')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif($debt->status == 'partial')
                                        <span class="badge bg-warning">Cicilan</span>
                                    @else
                                        <span class="badge bg-danger">Belum Bayar</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="{{ route('debts.show', $debt->id) }}" class="btn btn-sm btn-soft-primary" title="Detail">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        @if($debt->status != 'paid')
                                            <button class="btn btn-sm btn-soft-success" data-bs-toggle="modal" data-bs-target="#bayarModal{{ $debt->id }}" title="Bayar/Cicil">
                                                <i class="mdi mdi-cash-plus"></i>
                                            </button>
                                        @endif
                                        @if($debt->phone && $type == 'receivable' && $debt->status != 'paid')
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $debt->phone) }}?text={{ urlencode('Halo ' . $debt->person_name . ', ini pengingat bahwa Anda masih memiliki tagihan sebesar Rp ' . number_format($debt->remaining, 0, ',', '.') . '. Mohon segera dilunasi ya. Terima kasih! 🙏') }}" target="_blank" class="btn btn-sm btn-soft-success" title="Tagih via WA">
                                                <i class="mdi mdi-whatsapp"></i>
                                            </a>
                                        @endif
                                        @if($debt->paid_amount == 0)
                                            <form action="{{ route('debts.destroy', $debt->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-soft-danger" title="Hapus"><i class="mdi mdi-delete"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Bayar --}}
                            @if($debt->status != 'paid')
                            <div class="modal fade" id="bayarModal{{ $debt->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('debts.pay', $debt->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Bayar/Cicil — {{ $debt->person_name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-info mb-3">
                                                    <strong>Sisa tagihan:</strong> Rp {{ number_format($debt->remaining, 0, ',', '.') }}
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nominal Pembayaran</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" name="amount" class="form-control" placeholder="0" required min="1" max="{{ $debt->remaining }}">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Catatan <span class="text-muted">(Opsional)</span></label>
                                                    <input type="text" name="notes" class="form-control" placeholder="Misal: Bayar lewat transfer BCA">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Konfirmasi Bayar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Hutang/Piutang --}}
<div class="modal fade" id="tambahDebt" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('debts.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Catat Hutang / Piutang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="debt_receivable" value="receivable" {{ $type == 'receivable' ? 'checked' : '' }}>
                                <label class="form-check-label text-success fw-bold" for="debt_receivable">Piutang (Orang hutang ke saya)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="debt_payable" value="payable" {{ $type == 'payable' ? 'checked' : '' }}>
                                <label class="form-check-label text-danger fw-bold" for="debt_payable">Hutang (Saya hutang ke orang)</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Orang</label>
                        <input type="text" name="person_name" class="form-control" placeholder="Misal: Bu Sari, Pak Joko" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No HP <span class="text-muted">(Opsional, untuk tagih via WA)</span></label>
                        <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Nominal</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="total_amount" class="form-control" placeholder="0" required min="1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jatuh Tempo <span class="text-muted">(Opsional)</span></label>
                        <input type="date" name="due_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan <span class="text-muted">(Opsional)</span></label>
                        <input type="text" name="notes" class="form-control" placeholder="Misal: Beli beras 3 karung">
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
