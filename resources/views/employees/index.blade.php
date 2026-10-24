@extends('layouts.app')
@section('title', 'Manajemen Karyawan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Manajemen Karyawan (Kasir)</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0">Tambah Kasir Baru</h5>
            </div>
            <div class="card-body">
                @if(!$hasPremium)
                    <div class="alert alert-warning text-center">
                        <i class="mdi mdi-lock-outline mdi-36px d-block mb-2 text-warning"></i>
                        Fitur Manajemen Kasir ini khusus pengguna Premium. Silakan Upgrade untuk menambah akun Kasir yang aman.
                    </div>
                @endif
                <form action="{{ route('employees.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" name="name" required {{ !$hasPremium ? 'disabled' : '' }}>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email (Untuk Login)</label>
                        <input type="email" class="form-control" name="email" required {{ !$hasPremium ? 'disabled' : '' }}>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Kasir</label>
                        <input type="password" class="form-control" name="password" required {{ !$hasPremium ? 'disabled' : '' }}>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" {{ !$hasPremium ? 'disabled' : '' }}>
                        <i class="bx bx-plus"></i> Tambah Karyawan
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Karyawan</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $emp)
                                <tr>
                                    <td>{{ $emp->name }}</td>
                                    <td>{{ $emp->email }}</td>
                                    <td><span class="badge bg-info">Kasir</span></td>
                                    <td>
                                        <form action="{{ route('employees.destroy', $emp->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus kasir ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada karyawan yang ditambahkan.</td>
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
