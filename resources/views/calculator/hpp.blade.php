@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                <i class="mdi mdi-calculator-variant text-primary me-2"></i> Kalkulator Bisnis HPP
            </h4>
        </div>
        <p class="text-muted">Hitung Harga Pokok Penjualan dan tentukan harga jual yang tepat untuk produk Anda.</p>
    </div>
</div>

<div class="row">
    <!-- INPUT -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header bg-primary bg-gradient">
                <h5 class="card-title text-white mb-0"><i class="mdi mdi-pencil-outline me-1"></i> 1. Masukkan Data Modal</h5>
            </div>
            <div class="card-body">

                {{-- Bahan Baku --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="mdi mdi-package-variant-closed text-primary me-1"></i> Biaya Bahan Baku / Material</label>
                    <small class="text-muted d-block mb-2">Contoh: Tepung Rp 50.000, Gula Rp 30.000, Telur Rp 20.000 → total Rp 100.000</small>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="bahan_baku" placeholder="0" min="0">
                    </div>
                </div>

                {{-- Tenaga Kerja --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="mdi mdi-account-hard-hat text-warning me-1"></i> Biaya Tenaga Kerja <span class="badge bg-light text-muted">Opsional</span></label>
                    <small class="text-muted d-block mb-2">Upah karyawan untuk satu kali produksi ini. Kosongkan jika dikerjakan sendiri.</small>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="tenaga_kerja" placeholder="0" min="0">
                    </div>
                </div>

                {{-- Overhead --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="mdi mdi-flash text-info me-1"></i> Biaya Overhead (Kemasan, Listrik, Gas, dll) <span class="badge bg-light text-muted">Opsional</span></label>
                    <small class="text-muted d-block mb-2">Biaya tambahan di luar bahan baku: plastik, kardus, bensin antar, listrik oven, dll.</small>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="overhead" placeholder="0" min="0">
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold"><i class="mdi mdi-cube-outline text-success me-1"></i> Jumlah Porsi / Unit yang Dihasilkan</label>
                        <small class="text-muted d-block mb-2">Dari modal di atas, bisa jadi berapa porsi/bungkus?</small>
                        <input type="number" class="form-control" id="jumlah_unit" value="1" min="1">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold"><i class="mdi mdi-percent-outline text-danger me-1"></i> Target Keuntungan (%)</label>
                        <small class="text-muted d-block mb-2">Berapa persen laba yang Anda inginkan?</small>
                        <div class="input-group">
                            <input type="number" class="form-control" id="target_margin" value="20" min="0" max="500">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>

                <div class="d-grid mt-3">
                    <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn-hitung">
                        <i class="mdi mdi-calculator me-1"></i> HITUNG SEKARANG
                    </button>
                </div>
                <div class="text-center mt-2">
                    <button class="btn btn-link text-muted btn-sm" id="btn-reset">
                        <i class="mdi mdi-refresh"></i> Reset Semua Angka
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- OUTPUT -->
    <div class="col-xl-6">
        {{-- Hasil awalnya tersembunyi --}}
        <div id="hasil-area" style="display: none;">

            {{-- Kartu HPP Total --}}
            <div class="card bg-primary text-white text-center mb-3">
                <div class="card-body p-4">
                    <p class="text-white-50 mb-2 font-size-14">TOTAL MODAL (HPP KESELURUHAN)</p>
                    <h1 class="text-white fw-bold mb-1" id="out_hpp_total">Rp 0</h1>
                    <p class="text-white-50 mb-0 font-size-13" id="out_rumus_total"></p>
                </div>
            </div>

            {{-- Rincian Hasil --}}
            <div class="card border-primary mb-3">
                <div class="card-header bg-transparent border-primary">
                    <h5 class="card-title text-primary mb-0"><i class="mdi mdi-format-list-bulleted me-1"></i> 2. Hasil Perhitungan</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <tbody>
                            <tr>
                                <td class="ps-3 py-3">
                                    <strong>Modal per Unit (HPP/Unit)</strong><br>
                                    <small class="text-muted" id="out_rumus_unit">-</small>
                                </td>
                                <td class="text-end pe-3 py-3 fw-bold font-size-16" id="out_hpp_unit">Rp 0</td>
                            </tr>
                            <tr>
                                <td class="ps-3 py-3">
                                    <strong>Laba per Unit</strong><br>
                                    <small class="text-muted" id="out_rumus_laba">-</small>
                                </td>
                                <td class="text-end pe-3 py-3 fw-bold font-size-16 text-success" id="out_laba_unit">+ Rp 0</td>
                            </tr>
                            <tr class="table-primary">
                                <td class="ps-3 py-3">
                                    <strong class="font-size-16">✅ HARGA JUAL IDEAL</strong><br>
                                    <small class="text-muted" id="out_rumus_jual">-</small>
                                </td>
                                <td class="text-end pe-3 py-3 fw-bold font-size-22 text-primary" id="out_harga_jual">Rp 0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Rumus --}}
            <div class="card border-info">
                <div class="card-header bg-info bg-gradient">
                    <h5 class="card-title text-white mb-0"><i class="mdi mdi-book-open-page-variant me-1"></i> 3. Rumus yang Digunakan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-primary me-1">1</span> <strong>HPP Total</strong>
                        <div class="bg-light rounded p-2 mt-1 font-monospace font-size-13">
                            HPP Total = Bahan Baku + Tenaga Kerja + Overhead
                        </div>
                    </div>
                    <div class="mb-3">
                        <span class="badge bg-primary me-1">2</span> <strong>HPP per Unit</strong>
                        <div class="bg-light rounded p-2 mt-1 font-monospace font-size-13">
                            HPP/Unit = HPP Total ÷ Jumlah Unit
                        </div>
                    </div>
                    <div class="mb-3">
                        <span class="badge bg-primary me-1">3</span> <strong>Laba per Unit</strong>
                        <div class="bg-light rounded p-2 mt-1 font-monospace font-size-13">
                            Laba/Unit = HPP/Unit × (Target Margin ÷ 100)
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-success me-1">4</span> <strong>Harga Jual Ideal</strong>
                        <div class="bg-light rounded p-2 mt-1 font-monospace font-size-13">
                            Harga Jual = HPP/Unit + Laba/Unit
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <a href="#" id="btn-tambah-produk" class="btn btn-primary" style="display:none;">
                    <i class="mdi mdi-plus"></i> Tambah ke Daftar Produk
                </a>
            </div>
        </div>

        {{-- Placeholder sebelum ditekan Hitung --}}
        <div id="placeholder-area">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle font-size-28">
                            <i class="mdi mdi-arrow-left-bold"></i>
                        </div>
                    </div>
                    <h5>Isi Data di Sebelah Kiri</h5>
                    <p class="text-muted mb-0">Masukkan rincian modal Anda, lalu tekan tombol <strong>"HITUNG SEKARANG"</strong> untuk melihat hasilnya di sini.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnHitung = document.getElementById('btn-hitung');
    const btnReset = document.getElementById('btn-reset');
    const hasilArea = document.getElementById('hasil-area');
    const placeholderArea = document.getElementById('placeholder-area');

    // Formatter Rupiah
    const rp = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);
    const num = (n) => new Intl.NumberFormat('id-ID').format(n);

    btnHitung.addEventListener('click', function() {
        const bahanBaku = parseFloat(document.getElementById('bahan_baku').value) || 0;
        const tenagaKerja = parseFloat(document.getElementById('tenaga_kerja').value) || 0;
        const overhead = parseFloat(document.getElementById('overhead').value) || 0;
        let jumlahUnit = parseInt(document.getElementById('jumlah_unit').value) || 1;
        if (jumlahUnit < 1) jumlahUnit = 1;
        const targetMargin = parseFloat(document.getElementById('target_margin').value) || 0;

        // Validasi minimal
        if (bahanBaku <= 0 && tenagaKerja <= 0 && overhead <= 0) {
            alert('Mohon isi minimal satu kolom biaya (Bahan Baku / Tenaga Kerja / Overhead).');
            return;
        }

        // ========== RUMUS ==========
        // 1. HPP Total
        const hppTotal = bahanBaku + tenagaKerja + overhead;

        // 2. HPP per Unit
        const hppUnit = hppTotal / jumlahUnit;

        // 3. Laba per Unit (mark-up sederhana)
        const labaUnit = hppUnit * (targetMargin / 100);

        // 4. Harga Jual
        const hargaJual = hppUnit + labaUnit;

        // ========== TAMPILKAN ==========
        document.getElementById('out_hpp_total').innerText = rp(hppTotal);
        document.getElementById('out_rumus_total').innerText =
            rp(bahanBaku) + ' + ' + rp(tenagaKerja) + ' + ' + rp(overhead) + ' = ' + rp(hppTotal);

        document.getElementById('out_hpp_unit').innerText = rp(Math.ceil(hppUnit));
        document.getElementById('out_rumus_unit').innerText =
            rp(hppTotal) + ' ÷ ' + num(jumlahUnit) + ' unit = ' + rp(Math.ceil(hppUnit));

        document.getElementById('out_laba_unit').innerText = '+ ' + rp(Math.ceil(labaUnit));
        document.getElementById('out_rumus_laba').innerText =
            rp(Math.ceil(hppUnit)) + ' × ' + targetMargin + '% = ' + rp(Math.ceil(labaUnit));

        document.getElementById('out_harga_jual').innerText = rp(Math.ceil(hargaJual));
        document.getElementById('out_rumus_jual').innerText =
            rp(Math.ceil(hppUnit)) + ' + ' + rp(Math.ceil(labaUnit)) + ' = ' + rp(Math.ceil(hargaJual));

        // Tampilkan hasil, sembunyikan placeholder
        placeholderArea.style.display = 'none';
        hasilArea.style.display = 'block';

        // Update tombol Tambah ke Produk
        const btnTambahProduk = document.getElementById('btn-tambah-produk');
        const productUrl = '{{ route("products.index") }}' + '?hpp=' + Math.ceil(hppUnit) + '&jual=' + Math.ceil(hargaJual);
        btnTambahProduk.href = productUrl;
        btnTambahProduk.style.display = 'inline-block';

        // Scroll ke hasil di mobile
        hasilArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    btnReset.addEventListener('click', function() {
        document.getElementById('bahan_baku').value = '';
        document.getElementById('tenaga_kerja').value = '';
        document.getElementById('overhead').value = '';
        document.getElementById('jumlah_unit').value = 1;
        document.getElementById('target_margin').value = 20;

        hasilArea.style.display = 'none';
        placeholderArea.style.display = 'block';
    });
});
</script>
@endpush
