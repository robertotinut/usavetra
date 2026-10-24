@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                <i class="mdi mdi-account-details text-primary me-2"></i>
                Detail {{ $debt->type == 'receivable' ? 'Piutang' : 'Hutang' }} — {{ $debt->person_name }}
            </h4>
            <a href="{{ route('debts.index', ['type' => $debt->type]) }}" class="btn btn-light btn-sm">
                <i class="mdi mdi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-5">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Informasi</h5></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:40%">Nama</td>
                        <td><strong>{{ $debt->person_name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">No HP</td>
                        <td>{{ $debt->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jenis</td>
                        <td>
                            @if($debt->type == 'receivable')
                                <span class="badge bg-success">Piutang</span>
                            @else
                                <span class="badge bg-danger">Hutang</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Dibuat</td>
                        <td>{{ $debt->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jatuh Tempo</td>
                        <td>
                            @if($debt->due_date)
                                <span class="{{ $debt->due_date->isPast() && $debt->status != 'paid' ? 'text-danger fw-bold' : '' }}">
                                    {{ $debt->due_date->format('d M Y') }}
                                    @if($debt->due_date->isPast() && $debt->status != 'paid')
                                        <span class="badge bg-danger ms-1">Lewat Jatuh Tempo!</span>
                                    @endif
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Catatan</td>
                        <td>{{ $debt->notes ?? '-' }}</td>
                    </tr>
                </table>

                <hr>

                <div class="row text-center">
                    <div class="col-4">
                        <p class="text-muted mb-1 font-size-12">Total</p>
                        <h6>Rp {{ number_format($debt->total_amount, 0, ',', '.') }}</h6>
                    </div>
                    <div class="col-4">
                        <p class="text-muted mb-1 font-size-12">Dibayar</p>
                        <h6 class="text-success">Rp {{ number_format($debt->paid_amount, 0, ',', '.') }}</h6>
                    </div>
                    <div class="col-4">
                        <p class="text-muted mb-1 font-size-12">Sisa</p>
                        <h6 class="text-danger">Rp {{ number_format($debt->remaining, 0, ',', '.') }}</h6>
                    </div>
                </div>

                <div class="mt-2">
                    <div class="progress" style="height: 10px;">
                        @php $persen = $debt->total_amount > 0 ? ($debt->paid_amount / $debt->total_amount) * 100 : 0; @endphp
                        <div class="progress-bar bg-success" style="width: {{ $persen }}%"></div>
                    </div>
                    <small class="text-muted">{{ round($persen) }}% terbayar</small>
                </div>

                @if($debt->status != 'paid')
                <div class="mt-4">
                    <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#bayarModalDetail">
                        <i class="mdi mdi-cash-plus me-1"></i> Bayar / Cicil
                    </button>
                </div>
                @else
                <div class="mt-4">
                    <div class="alert alert-success text-center mb-0">
                        <i class="mdi mdi-check-circle me-1"></i> LUNAS
                    </div>
                </div>
                @endif

                <div class="row mt-3">
                    <div class="col-12">
                        <p class="text-muted mb-1 font-size-13">Status:
                            @if($debt->status == 'paid')
                                <span class="badge bg-success">Lunas</span>
                            @elseif($debt->status == 'partial')
                                <span class="badge bg-warning">Cicilan</span>
                            @else
                                <span class="badge bg-danger">Belum Bayar</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Pembayaran --}}
    <div class="col-xl-7">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Riwayat Pembayaran / Cicilan</h5></div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($debt->payments->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($debt->payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-success fw-bold">+ Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>{{ $payment->notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="avatar-md mx-auto mb-3">
                        <div class="avatar-title bg-light text-muted rounded-circle font-size-24">
                            <i class="mdi mdi-cash-remove"></i>
                        </div>
                    </div>
                    <p class="text-muted">Belum ada riwayat pembayaran.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Bayar Detail --}}
@if($debt->status != 'paid')
<div class="modal fade" id="bayarModalDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('debts.pay', $debt->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bayar / Cicil — {{ $debt->person_name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Sisa tagihan:</strong> Rp {{ number_format($debt->remaining, 0, ',', '.') }}
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nominal Pembayaran</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount" class="form-control" required min="1" max="{{ $debt->remaining }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan <span class="text-muted">(Opsional)</span></label>
                        <input type="text" name="notes" class="form-control" placeholder="Misal: Transfer BCA">
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
@endsection
