@extends('layouts.app')
@section('title', 'Data Produk')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Data Produk</h4>
            <div class="page-title-right">
                @if(auth()->user()->role !== 'kasir')
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="mdi mdi-plus me-1"></i> Tambah Produk
                </button>
                @endif
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
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                @if(auth()->user()->role !== 'kasir')
                                <th>Harga Beli</th>
                                @endif
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr class="{{ $product->stock <= 0 ? 'table-danger' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="badge bg-secondary">{{ $product->product_code ?? '-' }}</span></td>
                                <td>
                                    <p class="mb-0 text-truncate {{ $product->stock <= 0 ? 'text-danger fw-bold' : '' }}" style="max-width: 200px;">
                                        {{ $product->name }}
                                        @if($product->stock <= 0)
                                            <i class="mdi mdi-alert-circle ms-1" title="Stok Habis / Minus"></i>
                                        @endif
                                    </p>
                                    <small class="text-muted">{{ $product->barcode ?? '-' }}</small>
                                </td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                @if(auth()->user()->role !== 'kasir')
                                <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                @endif
                                <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                <td>
                                    @if($product->stock <= 0)
                                        <span class="badge bg-danger"><i class="bx bx-error-circle"></i> Minus/Habis ({{ $product->stock }})</span>
                                    @elseif($product->stock <= $product->min_stock)
                                        <span class="badge bg-warning text-dark"><i class="bx bx-info-circle"></i> Tipis ({{ $product->stock }})</span>
                                    @else
                                        <span class="badge bg-success">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('products.show', $product->id) }}" class="text-info" title="Kartu Stok">
                                            <i class="mdi mdi-text-box-search-outline font-size-18"></i>
                                        </a>
                                        @if(auth()->user()->role !== 'kasir')
                                        <a href="javascript:void(0);" class="text-success" data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}" title="Edit Produk">
                                            <i class="mdi mdi-pencil font-size-18"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger border-0 bg-transparent p-0"><i class="mdi mdi-delete font-size-18"></i></button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $product->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route('products.update', $product->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $product->id }}">Edit Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="name" value="{{ $product->name }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Barcode (Opsional)</label>
                                                        <input type="text" class="form-control" name="barcode" value="{{ $product->barcode }}" placeholder="Scan atau ketik">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="category_id" required>
                                                            <option value="">-- Pilih Kategori --</option>
                                                            @foreach($categories as $cat)
                                                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Supplier (Opsional)</label>
                                                        <select class="form-select" name="supplier_id">
                                                            <option value="">-- Pilih Supplier --</option>
                                                            @foreach($suppliers as $sup)
                                                                <option value="{{ $sup->id }}" {{ $product->supplier_id == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Harga Modal/Beli <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" name="purchase_price" value="{{ $product->purchase_price }}" required min="0">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" name="selling_price" value="{{ $product->selling_price }}" required min="0">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" name="stock" value="{{ $product->stock }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Batas Peringatan Stok <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" name="min_stock" value="{{ $product->min_stock }}" required min="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Update Produk</button>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Barcode (Opsional)</label>
                            <input type="text" class="form-control" name="barcode" placeholder="Scan atau ketik">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" name="category_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier (Opsional)</label>
                            <select class="form-select" name="supplier_id">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Modal/Beli <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="purchase_price" value="{{ request('hpp', 0) }}" required min="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="selling_price" value="{{ request('jual', 0) }}" required min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="stock" value="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Batas Peringatan Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="min_stock" value="5" required min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
@if(request('hpp') || request('jual'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var createModal = new bootstrap.Modal(document.getElementById('createModal'));
        createModal.show();
    });
</script>
@endif
@endpush
@endsection
