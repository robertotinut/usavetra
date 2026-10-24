@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <h4 class="card-title mb-4">Data Pelanggan (Tenants)</h4>
                    </div>
                    <div class="col-sm-8">
                        <div class="text-sm-end">
                            <button type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2" data-bs-toggle="modal" data-bs-target="#addTenantModal"><i class="mdi mdi-plus me-1"></i> Tambah Pelanggan</button>
                        </div>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table datatable align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID Toko</th>
                                <th>Nama Toko</th>
                                <th>Pemilik</th>
                                <th>Email</th>
                                <th>Terdaftar Sejak</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                            <tr>
                                <td>#{{ str_pad($store->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $store->name }}</td>
                                <td>{{ $store->owner_name }}</td>
                                <td>{{ $store->owner_email }}</td>
                                <td>{{ \Carbon\Carbon::parse($store->created_at)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.tenants.show', $store->id) }}" class="btn btn-primary btn-sm">Lihat & Kelola</a>
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

<!-- Add Tenant Modal -->
<div class="modal fade" id="addTenantModal" tabindex="-1" aria-labelledby="addTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.tenants.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Daftarkan Toko Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Toko / Bisnis <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="store_name" required placeholder="Misal: Toko Serba Ada">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="owner_name" required placeholder="Nama lengkap pemilik">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required placeholder="email@contoh.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" required minlength="6" placeholder="Minimal 6 karakter">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Daftarkan Toko</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
