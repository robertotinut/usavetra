@extends('layouts.app')
@section('title', 'Mesin Kasir (POS)')

@push('css')
<link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    /* Hide number input arrows */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }

    /* Desktop vs Mobile Cart layout */
    @media (min-width: 1200px) {
        .mobile-only-cart-trigger { display: none !important; }
        .desktop-cart-col { display: block !important; }
        .pos-grid-col { max-height: calc(100vh - 170px); overflow-y: auto; padding-right: 8px; }
    }

    @media (max-width: 1199.98px) {
        .desktop-cart-col { display: none !important; }
        .page-title-box { display: none !important; }
    }

    .search-input-group {
        position: relative;
    }
    .search-input-group i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 18px;
    }
    .search-input-group input {
        padding-left: 42px;
        border-radius: 12px;
        height: 44px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        font-size: 14px;
    }
    .search-input-group input:focus {
        border-color: #0ca678;
        box-shadow: 0 0 0 3px rgba(12, 166, 120, 0.15);
    }
</style>
@endpush

@section('content')

<!-- Desktop Title (Hidden on mobile) -->
<div class="row d-none d-xl-flex">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between py-2">
            <h4 class="mb-0 font-size-18 fw-bold">Mesin Kasir (POS)</h4>
            <div class="page-title-right">
                <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm rounded-pill px-3">
                    <i class="mdi mdi-arrow-left me-1"></i> Riwayat Transaksi
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Kolom Kiri: Produk & Pencarian (Full Width di Mobile, 7-Kolom di Desktop) -->
    <div class="col-xl-7 col-12 pos-grid-col">
        
        <!-- Search & Action Bar -->
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="search-input-group flex-grow-1">
                <i class="mdi mdi-magnify"></i>
                <input type="text" class="form-control" id="search-product" placeholder="Cari nama barang atau barcode...">
            </div>
            <button type="button" class="btn btn-primary d-flex align-items-center gap-1" style="height: 44px; border-radius: 12px; font-weight: 600; white-space: nowrap;" onclick="addCustomItemPrompt()">
                <i class="mdi mdi-plus-circle-outline font-size-18"></i>
                <span class="d-none d-sm-inline">Item</span> Manual
            </button>
        </div>

        <!-- Horizontal Category Pills -->
        @if(isset($categories) && $categories->count() > 0)
        <div class="pos-categories-wrapper mb-2">
            <button type="button" class="category-pill-btn active" data-category="all" onclick="filterCategory('all', this)">
                <i class="mdi mdi-view-grid-outline"></i> Semua
            </button>
            @foreach($categories as $cat)
            <button type="button" class="category-pill-btn" data-category="{{ $cat->id }}" onclick="filterCategory('{{ $cat->id }}', this)">
                <i class="mdi mdi-tag-outline"></i> {{ $cat->name }}
            </button>
            @endforeach
        </div>
        @endif

        <!-- Product Grid (2 Kolom di Mobile, 3 Kolom di Layar Lebar) -->
        <div class="pos-products-grid" id="product-list">
            @forelse($products as $prod)
            <div class="pos-card product-card {{ $prod->stock <= 0 ? 'stock-empty' : '' }}" 
                 id="product-card-{{ $prod->id }}" 
                 data-id="{{ $prod->id }}"
                 data-name="{{ strtolower($prod->name) }}" 
                 data-barcode="{{ $prod->barcode }}"
                 data-category="{{ $prod->category_id ?? 'uncategorized' }}"
                 onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->name) }}', {{ $prod->selling_price }})">
                
                <!-- Dynamic Item Qty Badge in Cart -->
                <div class="item-qty-badge" id="card-qty-badge-{{ $prod->id }}" style="display: none;">0</div>

                <div class="card-icon-container" id="product-icon-{{ $prod->id }}">
                    <i class="mdi {{ $prod->stock <= 0 ? 'mdi-alert-circle-outline' : 'mdi-package-variant-closed' }}"></i>
                </div>

                <div class="product-title" title="{{ $prod->name }}">{{ $prod->name }}</div>
                <div class="product-code">{{ $prod->product_code ?? '-' }}</div>
                <div class="product-price">Rp {{ number_format($prod->selling_price, 0, ',', '.') }}</div>

                <div class="text-center mt-1">
                    <span id="prod-badge-{{ $prod->id }}" class="stock-pill {{ $prod->stock <= 0 ? 'bg-danger-subtle text-danger' : ($prod->stock <= $prod->min_stock ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success') }}">
                        {{ $prod->stock <= 0 ? 'Habis (0)' : 'Stok: ' . $prod->stock }}
                    </span>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="avatar-md mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-light text-muted font-size-24">
                        <i class="mdi mdi-package-variant-remove"></i>
                    </span>
                </div>
                <p class="text-muted fw-medium">Belum ada data produk.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Kolom Kanan: Keranjang di Desktop (Tampil di Layar >= 1200px) -->
    <div class="col-xl-5 desktop-cart-col">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="card-title m-0 fw-bold font-size-16"><i class="mdi mdi-cart-outline me-2 text-primary"></i>Keranjang Belanja</h5>
                    <span class="badge bg-primary-subtle text-primary rounded-pill font-size-12 px-3 py-1.5" id="desktop-item-count">0 Item</span>
                </div>

                <!-- Shared Form Template Content via JS / DOM -->
                <div id="desktop-cart-container">
                    <!-- Form rendered here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- FLOATING STICKY CART BAR (Mobile Only) -->
<!-- ============================================================== -->
<div class="pos-mobile-cart-bar mobile-only-cart-trigger" id="mobile-cart-bar" style="display: none;" onclick="openCartBottomSheet()">
    <div class="cart-info-left">
        <div class="cart-icon-bubble">
            <i class="mdi mdi-cart-outline"></i>
            <span class="badge-total-qty" id="mobile-cart-badge-qty">0</span>
        </div>
        <div class="cart-prices">
            <span class="price-title">Total Belanja</span>
            <span class="price-total" id="mobile-cart-bar-total">Rp 0</span>
        </div>
    </div>
    <button type="button" class="btn-checkout-fast">
        <span>Bayar</span>
        <i class="mdi mdi-arrow-right font-size-16"></i>
    </button>
</div>

<!-- ============================================================== -->
<!-- MOBILE CHECKOUT & CART BOTTOM SHEET MODAL (Offcanvas) -->
<!-- ============================================================== -->
<div class="offcanvas offcanvas-bottom mobile-drawer" tabindex="-1" id="cartBottomSheet" style="height: 85vh; z-index: 1060;">
    <div class="offcanvas-header mobile-drawer-header">
        <div class="d-flex align-items-center gap-2">
            <i class="mdi mdi-cart-check font-size-22 text-primary"></i>
            <h5 class="offcanvas-title mb-0 font-size-16 fw-bold">Keranjang & Pembayaran</h5>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-3" style="overflow-y: auto;" id="mobile-cart-container">
        <!-- The single active POS form will reside here or in desktop container -->
    </div>
</div>

<!-- Master POS Form Element (Moved dynamically or unified) -->
<div id="pos-form-source" style="display: none;">
    <form action="{{ route('sales.store') }}" method="POST" id="pos-form">
        @csrf
        <input type="hidden" name="receipt_number" value="{{ $receiptNumber }}">
        <input type="hidden" name="date" value="{{ date('Y-m-d') }}">

        <!-- List Keranjang Belanja -->
        <div class="table-responsive mb-3" style="max-height: 220px; overflow-y: auto;">
            <table class="table align-middle table-nowrap mb-0" id="cart-table">
                <tbody id="cart-body">
                    <tr id="empty-cart-msg">
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="mdi mdi-cart-outline font-size-24 d-block mb-1"></i>
                            Keranjang masih kosong
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Total Tagihan -->
        <div class="p-3 rounded-3 mb-3" style="background: #f1fcf7; border: 1px dashed #63e6be;">
            <div class="d-flex justify-content-between align-items-center">
                <span class="font-size-13 text-muted fw-medium">Total Tagihan:</span>
                <h4 class="font-size-18 m-0 text-success fw-bold" id="grand-total-display">Rp 0</h4>
            </div>
            <input type="hidden" name="total_amount" id="total_amount" value="0">
        </div>

        <!-- Pelanggan & Metode Bayar -->
        <div class="row g-2 mb-3">
            <div class="col-7">
                <label class="form-label font-size-12 mb-1 text-muted">Nama Pelanggan (Opsional)</label>
                <input type="text" class="form-control form-control-sm rounded-2" name="customer_name" placeholder="Cth: Bu Siti / Umum">
            </div>
            <div class="col-5">
                <label class="form-label font-size-12 mb-1 text-muted">Metode Bayar</label>
                <select class="form-select form-select-sm rounded-2" name="payment_method">
                    <option value="Tunai">Tunai</option>
                    <option value="QRIS">QRIS</option>
                    <option value="Transfer">Transfer</option>
                </select>
            </div>
        </div>

        <!-- Input Uang Diterima & Tombol Cepat (Quick Cash) -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label font-size-12 mb-0 fw-bold">Uang Diterima (Rp)</label>
                <span class="font-size-11 text-muted">Pilih nominal cepat:</span>
            </div>

            <!-- Quick Cash Nominal Buttons -->
            <div class="quick-cash-container">
                <button type="button" class="quick-cash-btn active" data-amount="exact" onclick="setExactAmount(this)">Uang Pas</button>
                <button type="button" class="quick-cash-btn" data-amount="10000" onclick="setQuickCash(10000, this)">10.000</button>
                <button type="button" class="quick-cash-btn" data-amount="20000" onclick="setQuickCash(20000, this)">20.000</button>
                <button type="button" class="quick-cash-btn" data-amount="50000" onclick="setQuickCash(50000, this)">50.000</button>
                <button type="button" class="quick-cash-btn" data-amount="100000" onclick="setQuickCash(100000, this)">100.000</button>
                <button type="button" class="quick-cash-btn" data-amount="200000" onclick="setQuickCash(200000, this)">200.000</button>
            </div>

            <input type="number" class="form-control form-control-lg fw-bold text-success font-size-18 rounded-3" name="paid_amount" id="paid_amount" value="0" min="0" required placeholder="0">
        </div>

        <!-- Kembalian -->
        <div class="d-flex justify-content-between align-items-center p-2 px-3 rounded-2 bg-light mb-3">
            <span class="font-size-13 text-muted fw-medium">Kembalian:</span>
            <span class="font-size-16 fw-bold text-danger" id="change-display">Rp 0</span>
        </div>

        <!-- Tombol Bayar -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold py-2.5 font-size-15" id="btn-pay" disabled>
                <i class="mdi mdi-check-circle-outline me-1"></i> Proses Pembayaran
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    let cart = {};
    let productStocks = {
        @foreach($products as $p)
        "{{ $p->id }}": {{ $p->stock }},
        @endforeach
    };

    let bsCartOffcanvas = null;

    $(document).ready(function() {
        // Move form into appropriate container based on screen width
        setupResponsiveForm();
        $(window).resize(function() {
            setupResponsiveForm();
        });

        // Initialize Offcanvas
        const offcanvasEl = document.getElementById('cartBottomSheet');
        if(offcanvasEl && typeof bootstrap !== 'undefined') {
            bsCartOffcanvas = new bootstrap.Offcanvas(offcanvasEl);
        }
    });

    function setupResponsiveForm() {
        let form = $('#pos-form');
        if ($(window).width() >= 1200) {
            if ($('#desktop-cart-container').children('#pos-form').length === 0) {
                $('#desktop-cart-container').append(form);
            }
        } else {
            if ($('#mobile-cart-container').children('#pos-form').length === 0) {
                $('#mobile-cart-container').append(form);
            }
        }
    }

    function openCartBottomSheet() {
        if (bsCartOffcanvas) {
            bsCartOffcanvas.show();
        } else {
            $('#cartBottomSheet').addClass('show');
        }
    }

    // Format Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // Tambah ke keranjang
    function addToCart(id, name, price) {
        let stock = productStocks[id];
        
        // Cek stok habis
        if (stock < 1 && !cart[id]) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Sistem Kosong!',
                text: 'Stok barang ini tercatat 0. Tetap lanjutkan penjualan (stok akan minus)?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0ca678',
                cancelButtonColor: '#94a3b8',
            }).then((result) => {
                if (result.isConfirmed) {
                    processAddToCart(id, name, price, stock);
                }
            });
            return;
        }
        
        processAddToCart(id, name, price, stock);
    }
    
    function processAddToCart(id, name, price, stock) {
        $('#empty-cart-msg').hide();

        if (cart[id]) {
            changeQty(id, 1);
        } else {
            cart[id] = {
                id: id,
                name: name,
                price: price,
                qty: 1,
                stock: stock,
                subtotal: price
            };
            renderCart();
        }
        
        // Haptic feedback if supported
        if (navigator.vibrate) {
            navigator.vibrate(20);
        }
    }

    // Hapus baris di keranjang
    function removeCartItem(id) {
        delete cart[id];
        renderCart();
        
        if (Object.keys(cart).length === 0) {
            $('#empty-cart-msg').show();
        }
    }

    // Update Qty secara manual
    function updateQty(id, newQty) {
        let qty = parseInt(newQty);
        if (isNaN(qty) || qty < 1) qty = 1;
        
        if (qty > cart[id].stock && cart[id].stock > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Terbatas',
                text: 'Kuantitas (' + qty + ') melebihi stok sistem (' + cart[id].stock + '). Lanjutkan?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0ca678',
                cancelButtonColor: '#94a3b8',
            }).then((result) => {
                if (result.isConfirmed) {
                    cart[id].qty = qty;
                    cart[id].subtotal = qty * cart[id].price;
                    renderCart();
                } else {
                    let revertedQty = cart[id].stock > 0 ? cart[id].stock : 1;
                    cart[id].qty = revertedQty;
                    cart[id].subtotal = revertedQty * cart[id].price;
                    renderCart();
                }
            });
            return;
        }
        
        cart[id].qty = qty;
        cart[id].subtotal = qty * cart[id].price;
        renderCart();
    }

    // Tambah/Kurang Qty dengan tombol
    function changeQty(id, delta) {
        let newQty = cart[id].qty + delta;
        if (newQty <= 0) {
            removeCartItem(id);
            return;
        }
        updateQty(id, newQty);
    }

    // Render ulang HTML keranjang & sinkronkan UI card badges
    function renderCart() {
        $('.cart-item-row').remove();
        let grandTotal = 0;
        let totalItemsCount = 0;
        let rowIdx = 0;

        // Reset all card badges & classes
        $('.pos-card').removeClass('in-cart');
        $('.item-qty-badge').hide().text('0');

        for (let id in cart) {
            let item = cart[id];
            grandTotal += item.subtotal;
            totalItemsCount += item.qty;
            
            // Highlight on product card
            let card = $('#product-card-' + id);
            if (card.length) {
                card.addClass('in-cart');
                $('#card-qty-badge-' + id).text(item.qty).show();
            }

            let tr = `
                <tr class="cart-item-row">
                    <td>
                        <div class="fw-bold font-size-13 text-truncate" style="max-width: 140px;">${item.name}</div>
                        <div class="text-muted font-size-11">Rp ${formatRupiah(item.price)}</div>
                        <input type="hidden" name="products[${rowIdx}][id]" value="${item.id}">
                        <input type="hidden" name="products[${rowIdx}][price]" value="${item.price}">
                        ${item.is_custom ? `<input type="hidden" name="products[${rowIdx}][is_custom]" value="1"><input type="hidden" name="products[${rowIdx}][name]" value="${item.name}">` : ''}
                    </td>
                    <td style="width: 110px;">
                        <div class="input-group input-group-sm" style="flex-wrap: nowrap;">
                            <button class="btn btn-light border px-2 text-dark" type="button" onclick="changeQty('${item.id}', -1)">
                                <i class="mdi mdi-minus"></i>
                            </button>
                            <input type="number" class="form-control text-center px-1 fw-bold font-size-12" name="products[${rowIdx}][quantity]" value="${item.qty}" min="1" onchange="updateQty('${item.id}', this.value)">
                            <button class="btn btn-light border px-2 text-dark" type="button" onclick="changeQty('${item.id}', 1)">
                                <i class="mdi mdi-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="text-end fw-bold text-dark font-size-13">Rp ${formatRupiah(item.subtotal)}</td>
                    <td class="text-end" style="width: 30px;">
                        <button type="button" class="btn btn-sm btn-soft-danger p-1" onclick="removeCartItem('${item.id}')">
                            <i class="mdi mdi-close font-size-14"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#cart-body').append(tr);
            rowIdx++;
        }

        $('#total_amount').val(grandTotal);
        $('#grand-total-display').text('Rp ' + formatRupiah(grandTotal));
        $('#desktop-item-count').text(totalItemsCount + ' Item');

        // Update mobile floating cart bar
        if (totalItemsCount > 0) {
            $('#mobile-cart-badge-qty').text(totalItemsCount);
            $('#mobile-cart-bar-total').text('Rp ' + formatRupiah(grandTotal));
            $('#mobile-cart-bar').fadeIn(150);
        } else {
            $('#mobile-cart-bar').fadeOut(150);
            if (bsCartOffcanvas && $('#cartBottomSheet').hasClass('show')) {
                // optionally keep open or close
            }
        }

        calculateChange();
    }

    // Quick Cash Nominals & Dynamic Active States
    function updateQuickCashActive(amount) {
        $('.quick-cash-btn').removeClass('active');
        let total = parseFloat($('#total_amount').val()) || 0;

        if (amount === 'exact' || (amount == total && total > 0)) {
            $('.quick-cash-btn[data-amount="exact"]').addClass('active');
        } else {
            $(`.quick-cash-btn[data-amount="${amount}"]`).addClass('active');
        }
    }

    function setExactAmount(btn) {
        let total = parseFloat($('#total_amount').val()) || 0;
        $('#paid_amount').val(total);
        updateQuickCashActive('exact');
        calculateChange();
    }

    function setQuickCash(amount, btn) {
        $('#paid_amount').val(amount);
        updateQuickCashActive(amount);
        calculateChange();
    }

    // Hitung Kembalian
    function calculateChange() {
        let total = parseFloat($('#total_amount').val()) || 0;
        let paid = parseFloat($('#paid_amount').val()) || 0;
        let change = paid - total;

        let hasItems = Object.keys(cart).length > 0;

        if (change < 0) {
            $('#change-display').text('Kurang: Rp ' + formatRupiah(Math.abs(change))).removeClass('text-success').addClass('text-danger');
            $('#btn-pay').prop('disabled', true);
        } else {
            $('#change-display').text('Rp ' + formatRupiah(change)).removeClass('text-danger').addClass('text-success');
            $('#btn-pay').prop('disabled', !hasItems || paid < total);
        }
    }

    $('#paid_amount').on('input keyup change', function() {
        let paidVal = parseFloat($(this).val()) || 0;
        updateQuickCashActive(paidVal);
        calculateChange();
    });

    // Category Filter Chips
    function filterCategory(catId, btn) {
        $('.category-pill-btn').removeClass('active');
        $(btn).addClass('active');

        let keyword = $('#search-product').val().toLowerCase();

        $('.product-card').each(function() {
            let productCat = $(this).data('category');
            let name = $(this).data('name');
            let barcode = $(this).data('barcode') ? $(this).data('barcode').toString().toLowerCase() : '';

            let matchCat = (catId === 'all' || productCat == catId);
            let matchSearch = (name.includes(keyword) || barcode.includes(keyword));

            if (matchCat && matchSearch) {
                $(this).fadeIn(100);
            } else {
                $(this).hide();
            }
        });
    }

    // Fitur Pencarian Realtime
    $('#search-product').on('keyup input', function() {
        let keyword = $(this).val().toLowerCase();
        let activeCat = $('.category-pill-btn.active').data('category') || 'all';

        $('.product-card').each(function() {
            let name = $(this).data('name');
            let barcode = $(this).data('barcode') ? $(this).data('barcode').toString().toLowerCase() : '';
            let productCat = $(this).data('category');

            let matchCat = (activeCat === 'all' || productCat == activeCat);
            let matchSearch = (name.includes(keyword) || barcode.includes(keyword));

            if (matchCat && matchSearch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // AJAX Form Submit
    $('#pos-form').on('submit', function(e) {
        e.preventDefault();
        
        let btn = $('#btn-pay');
        btn.html('<i class="mdi mdi-loading mdi-spin me-1"></i> Memproses...').prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    if (bsCartOffcanvas) {
                        bsCartOffcanvas.hide();
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Transaksi Berhasil!',
                        text: response.message,
                        showCancelButton: true,
                        confirmButtonText: '<i class="mdi mdi-printer"></i> Cetak Struk',
                        cancelButtonText: 'Selesai',
                        confirmButtonColor: '#0ca678',
                        cancelButtonColor: '#94a3b8'
                    }).then((result) => {
                        if (result.isConfirmed && response.sale_id) {
                            window.open('/sales/' + response.sale_id + '/print', '_blank');
                        }
                    });
                    
                    // Reset
                    cart = {};
                    renderCart();
                    $('#pos-form')[0].reset();
                    $('#paid_amount').val(0);
                    calculateChange();
                    
                    if(response.new_receipt) {
                        $('input[name="receipt_number"]').val(response.new_receipt);
                    }
                    
                    if(response.updated_stocks) {
                        for(let id in response.updated_stocks) {
                            let newStock = response.updated_stocks[id];
                            productStocks[id] = newStock;
                            
                            let badge = $('#prod-badge-' + id);
                            let card = $('#product-card-' + id);
                            let icon = $('#product-icon-' + id);

                            if (newStock <= 0) {
                                badge.text('Habis (' + newStock + ')').removeClass('bg-success-subtle text-success bg-warning-subtle text-warning').addClass('bg-danger-subtle text-danger');
                                card.addClass('stock-empty');
                                icon.find('i').removeClass('mdi-package-variant-closed').addClass('mdi-alert-circle-outline');
                            } else {
                                badge.text('Stok: ' + newStock).removeClass('bg-danger-subtle text-danger').addClass('bg-success-subtle text-success');
                                card.removeClass('stock-empty');
                                icon.find('i').removeClass('mdi-alert-circle-outline').addClass('mdi-package-variant-closed');
                            }
                        }
                    }
                }
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan sistem.';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: msg,
                    confirmButtonColor: '#0ca678'
                });
            },
            complete: function() {
                if (Object.keys(cart).length === 0) {
                    btn.html('<i class="mdi mdi-check-circle-outline me-1"></i> Proses Pembayaran').prop('disabled', true);
                } else {
                    btn.html('<i class="mdi mdi-check-circle-outline me-1"></i> Proses Pembayaran').prop('disabled', false);
                }
            }
        });
    });

    function addCustomItemPrompt() {
        Swal.fire({
            title: 'Tambah Item Manual',
            html: `
                <div class="mb-3 text-start">
                    <label class="form-label font-size-12">Nama Barang</label>
                    <input type="text" id="custom-name" class="form-control" placeholder="Cth: Kerupuk / Jasa Kirim">
                </div>
                <div class="mb-2 text-start">
                    <label class="form-label font-size-12">Harga Satuan (Rp)</label>
                    <input type="number" id="custom-price" class="form-control" placeholder="Cth: 5000">
                </div>
            `,
            confirmButtonText: 'Tambahkan',
            confirmButtonColor: '#0ca678',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const name = Swal.getPopup().querySelector('#custom-name').value;
                const price = Swal.getPopup().querySelector('#custom-price').value;
                if (!name || !price) {
                    Swal.showValidationMessage(`Harap isi nama dan harga barang`);
                }
                return { name: name, price: parseInt(price) }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let customId = 'custom_' + Date.now();
                
                $('#empty-cart-msg').hide();
                
                cart[customId] = {
                    id: customId,
                    name: result.value.name,
                    price: result.value.price,
                    qty: 1,
                    stock: 99999,
                    subtotal: result.value.price,
                    is_custom: true
                };
                
                renderCart();
            }
        });
    }
</script>
@endpush
