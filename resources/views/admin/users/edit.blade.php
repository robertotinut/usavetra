@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Pengguna</h4>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ubah Role</label>
                        <select name="role" class="form-select" required>
                            <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="tenant" {{ $user->role == 'tenant' ? 'selected' : '' }}>Pemilik / Tenant</option>
                            <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Toko Asal (Kosongkan jika Super Admin)</label>
                        <select name="store_id" class="form-select">
                            <option value="">-- Pilih Toko --</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ $user->store_id == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reset Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Isi hanya jika ingin mengubah password">
                        <small class="text-muted">Minimal 6 karakter.</small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light ms-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
