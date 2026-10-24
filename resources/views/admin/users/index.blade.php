@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Manajemen Pengguna</h4>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Tambah Pengguna</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Toko Asal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'super_admin')
                                        <span class="badge bg-danger">Super Admin</span>
                                    @elseif($user->role == 'tenant')
                                        <span class="badge bg-primary">Pemilik / Tenant</span>
                                    @else
                                        <span class="badge bg-info">Kasir</span>
                                    @endif
                                </td>
                                <td>{{ $user->store ? $user->store->name : '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    
                                    @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen?')">Hapus</button>
                                    </form>
                                    @endif
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
@endsection
