@extends('layouts.app')
@section('title', 'Input Kulakan Baru')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Input Kulakan Baru</h4>
            <div class="page-title-right">
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary btn-sm"><i class="mdi mdi-arrow-left me-1"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('purchases.store') }}" method="POST">
    @csrf
    <div class="row">
        <!-- Bagian Kiri: Form Header -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Nota</h5>
                    
                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">No. Invoice <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="invoice_number" value="{{ old('invoice_number', $invoiceNumber) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Kulakan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Supplier (Opsional)</label>
                        <select class="form-select" name="supplier_id">
                            <option value="">-- Tanpa Supplier --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea class="form-control" name="notes" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan: Daftar Barang -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Daftar Barang Dibeli</h5>
                        <button type="button" class="btn btn-success btn-sm" id="btn-add-row"><i class="mdi mdi-plus"></i> Tambah Baris</button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="items-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%">Pilih Barang</th>
                                    <th style="width: 15%">Qty</th>
                                    <th style="width: 20%">Harga Beli (Rp)</th>
                                    <th style="width: 20%">Subtotal (Rp)</th>
                                    <th style="width: 5%">Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="items-body">
                                <!-- Baris dinamis akan muncul di sini -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end font-size-16">Total Keseluruhan :</th>
                                    <th colspan="2">
                                        <input type="number" class="form-control fw-bold text-end" id="grand-total" name="total_amount" value="0" readonly required>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-md">Simpan Kulakan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Template Data Produk (Disembunyikan) untuk JS -->
<div id="product-options" style="display: none;">
    <option value="">-- Pilih Barang --</option>
    @foreach($products as $prod)
        <option value="{{ $prod->id }}" data-price="{{ $prod->purchase_price }}">{{ $prod->name }}</option>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let rowIdx = 0;
    const productOptions = $('#product-options').html();

    function addRow() {
        const tr = `
            <tr id="row-${rowIdx}">
                <td>
                    <select class="form-select product-select" name="products[${rowIdx}][id]" required>
                        ${productOptions}
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control qty-input" name="products[${rowIdx}][quantity]" value="1" min="1" required>
                </td>
                <td>
                    <input type="number" class="form-control price-input" name="products[${rowIdx}][price]" value="0" min="0" required>
                </td>
                <td>
                    <input type="number" class="form-control subtotal-input text-end" value="0" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="mdi mdi-trash-can"></i></button>
                </td>
            </tr>
        `;
        $('#items-body').append(tr);
        rowIdx++;
    }

    // Tambah 1 baris pertama secara default
    addRow();

    // Event Tambah Baris
    $('#btn-add-row').on('click', function() {
        addRow();
    });

    // Event Hapus Baris
    $('#items-body').on('click', '.btn-remove-row', function() {
        $(this).closest('tr').remove();
        calculateGrandTotal();
    });

    // Event saat pilih produk, set harga default
    $('#items-body').on('change', '.product-select', function() {
        const selectedOption = $(this).find('option:selected');
        const price = selectedOption.data('price') || 0;
        const tr = $(this).closest('tr');
        tr.find('.price-input').val(price);
        calculateRowSubtotal(tr);
    });

    // Event saat ubah Qty atau Harga
    $('#items-body').on('input', '.qty-input, .price-input', function() {
        calculateRowSubtotal($(this).closest('tr'));
    });

    function calculateRowSubtotal(tr) {
        const qty = parseFloat(tr.find('.qty-input').val()) || 0;
        const price = parseFloat(tr.find('.price-input').val()) || 0;
        const subtotal = qty * price;
        tr.find('.subtotal-input').val(subtotal);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        $('.subtotal-input').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#grand-total').val(total);
    }
});
</script>
@endpush
