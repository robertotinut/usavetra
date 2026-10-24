@extends('layouts.app')
@section('title', 'Data Supplier')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Data Supplier</h4>
            <div class="page-title-right">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="mdi mdi-plus me-1"></i> Tambah Supplier
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table datatable align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20px;">No</th>
                                <th>Nama Supplier</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->phone ?? '-' }}</td>
                                <td>{{ Str::limit($supplier->address, 50) ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-3">
                                        <a href="javascript:void(0);" class="text-success" data-bs-toggle="modal" data-bs-target="#editModal{{ $supplier->id }}">
                                            <i class="mdi mdi-pencil font-size-18"></i>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger border-0 bg-transparent p-0"><i class="mdi mdi-delete font-size-18"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $supplier->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $supplier->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $supplier->id }}">Edit Supplier</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="name" value="{{ $supplier->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">No. Telepon / HP</label>
                                                    <input type="text" class="form-control" name="phone" value="{{ $supplier->phone }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Alamat</label>
                                                    <textarea class="form-control" name="address" rows="3">{{ $supplier->address }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Update Supplier</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon / HP</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="address" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
