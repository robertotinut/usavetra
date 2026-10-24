@extends('layouts.app')
@section('title', 'Buat Master Data Baru')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Buat Master Data Baru</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pending-products.index') }}">Item Tertunda</a></li>
                    <li class="breadcrumb-item active">Buat Master Data</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-4">Lengkapi form berikut. Setelah disimpan, semua riwayat transaksi untuk item <strong>"{{ $customName }}"</strong> akan otomatis disinkronisasi ke produk baru ini.</p>

                <form action="{{ route('pending-products.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="custom_name_original" value="{{ $customName }}">

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $customName) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="supplier_id" class="form-label">Supplier (Opsional)</label>
                            <select class="form-select" id="supplier_id" name="supplier_id">
                                <option value="">Pilih Supplier...</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="barcode" class="form-label">Barcode (Opsional)</label>
                        <input type="text" class="form-control" id="barcode" name="barcode" value="{{ old('barcode') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="purchase_price" class="form-label">Harga Beli / HPP (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="selling_price" class="form-label">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="selling_price" name="selling_price" value="{{ old('selling_price', $price) }}" required min="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', 0) }}" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="min_stock" class="form-label">Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="min_stock" name="min_stock" value="{{ old('min_stock', 5) }}" required min="0">
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('pending-products.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan & Sinkronisasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
