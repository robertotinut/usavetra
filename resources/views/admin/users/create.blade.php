@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Tambah Pengguna Baru</h4>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                        <small class="text-muted">Minimal 6 karakter.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih Role</label>
                        <select name="role" class="form-select" required>
                            <option value="tenant">Pemilik / Tenant</option>
                            <option value="kasir">Kasir</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Toko Asal (Opsional untuk Super Admin)</label>
                        <select name="store_id" class="form-select">
                            <option value="">-- Pilih Toko --</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Tambahkan Pengguna</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light ms-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
