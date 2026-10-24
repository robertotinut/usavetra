@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Detail Toko: {{ $store->name }}</h4>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <h5>Aktivasi Paket Langganan</h5>
                        <form action="{{ route('admin.tenants.subscribe', $store->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Pilih Paket</label>
                                <select class="form-select" name="package_id" required>
                                    <option value="">-- Pilih Paket --</option>
                                    @foreach($packages as $pkg)
                                        <option value="{{ $pkg->id }}">{{ $pkg->name }} (Rp {{ number_format($pkg->price, 0, ',', '.') }} - {{ $pkg->duration_days }} Hari)</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Aktifkan paket ini untuk toko {{ $store->name }}?')">Aktifkan Langganan Sekarang</button>
                        </form>
                    </div>
                </div>

                <hr class="my-4">

                <h5>Riwayat Berlangganan</h5>
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Paket</th>
                                <th>Mulai</th>
                                <th>Berakhir</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $sub)
                            <tr>
                                <td>{{ $sub->package->name }}</td>
                                <td>{{ $sub->start_date->format('d M Y') }}</td>
                                <td>{{ $sub->end_date->format('d M Y') }}</td>
                                <td>
                                    @if($sub->status == 'active')
                                        <span class="badge bg-success">Aktif</span>
                                        <form action="{{ route('admin.tenants.unsubscribe', $store->id) }}" method="POST" class="d-inline ms-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan paket ini sekarang?')">Nonaktifkan</button>
                                        </form>
                                    @elseif($sub->status == 'expired')
                                        <span class="badge bg-danger">Kedaluwarsa</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($sub->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Toko ini belum pernah berlangganan paket apapun.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">Kembali ke Daftar Pelanggan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
