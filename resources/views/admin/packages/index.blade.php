@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <h4 class="card-title">Kelola Paket Langganan</h4>
                    </div>
                    <div class="col-sm-8">
                        <div class="text-sm-end">
                            <button type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2" data-bs-toggle="modal" data-bs-target="#addModal"><i class="mdi mdi-plus me-1"></i> Tambah Paket</button>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Paket</th>
                                <th>Harga</th>
                                <th>Durasi (Hari)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packages as $pkg)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pkg->name }}</td>
                                <td>Rp {{ number_format($pkg->price, 0, ',', '.') }}</td>
                                <td>{{ $pkg->duration_days }} Hari</td>
                                <td>
                                    <span class="badge {{ $pkg->is_active ? 'bg-success' : 'bg-danger' }}">{{ $pkg->is_active ? 'Aktif' : 'Non-aktif' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data paket.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.packages.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Paket</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" name="price" value="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durasi (Hari)</label>
                        <input type="number" class="form-control" name="duration_days" value="30" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
