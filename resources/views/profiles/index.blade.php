@extends('layouts.app')
@section('title', 'Profil UMKM')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Profil UMKM</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profil UMKM</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Pengaturan Profil & Struk Kasir</h4>
                <p class="text-muted">Data di bawah ini akan digunakan sebagai identitas/kop pada struk pembelian cetak.</p>

                <form action="{{ route('profiles.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Toko / UMKM <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $store->name) }}" required>
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="mdi mdi-star text-warning"></i> Kustomisasi Struk (Premium)</h5>
                    
                    @if(!$hasPremiumStruk)
                        <div class="alert alert-warning text-center">
                            <i class="mdi mdi-lock-outline mdi-36px d-block mb-2 text-warning"></i>
                            Fitur Kustomisasi Logo dan Footer Struk hanya untuk pengguna Premium. Silakan Upgrade.
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo Toko (Opsional)</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*" {{ !$hasPremiumStruk ? 'disabled' : '' }}>
                        <small class="form-text text-muted">Akan dicetak di bagian paling atas struk.</small>
                    </div>

                    <div class="mb-4">
                        <label for="receipt_footer" class="form-label">Teks Penutup Struk (Footer)</label>
                        <textarea class="form-control" id="receipt_footer" name="receipt_footer" rows="3" placeholder="Contoh: Terima kasih atas kunjungannya!" {{ !$hasPremiumStruk ? 'disabled' : '' }}>{{ old('receipt_footer', $store->receipt_footer) }}</textarea>
                        <small class="form-text text-muted">Kalimat ini akan tampil di bagian paling bawah struk kasir Anda.</small>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary w-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Preview Struk Kasir</h5>
                
                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
                    .receipt-preview {
                        font-family: 'Inter', sans-serif;
                        color: #000;
                        width: 100%;
                        background: #fff;
                        padding: 15px;
                        border-radius: 4px;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        font-size: 11px;
                    }
                    .rp-header { margin-bottom: 15px; text-align: center; }
                    .rp-name { font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
                    .rp-address { font-size: 10px; line-height: 1.3; color: #222; }
                    
                    .rp-divider { border-bottom: 1px dashed #000; margin: 8px 0; }
                    
                    .rp-table { width: 100%; font-size: 11px; margin-bottom: 8px; }
                    .rp-table td { padding: 1px 0; }
                    
                    .rp-item-name { font-weight: 600; font-size: 11.5px; padding-bottom: 2px; }
                    
                    .rp-total { font-size: 13.5px; font-weight: 700; }
                    
                    .rp-footer { text-align: center; font-size: 10px; color: #222; line-height: 1.4; margin-top: 15px; }
                </style>
                
                <div class="receipt-preview mx-auto" style="max-width: 300px;">
                    <div class="rp-header">
                        @if($store->logo_path)
                            <img src="{{ Storage::url($store->logo_path) }}" alt="Logo" style="max-width: 100px; margin-bottom: 10px;">
                        @endif
                        <div class="rp-name" id="preview-name">{{ $store->name ?: 'NAMA TOKO' }}</div>
                        <div class="rp-address">
                            Jl. Contoh Alamat No. 123<br>
                            Telp: 081234567890
                        </div>
                    </div>
                    
                    <div class="rp-divider"></div>
                    
                    <table class="rp-table">
                        <tr>
                            <td class="text-start" style="width: 35%;">Struk</td>
                            <td class="text-end fw-semibold">TRC-2026...</td>
                        </tr>
                        <tr>
                            <td class="text-start">Kasir</td>
                            <td class="text-end">Admin</td>
                        </tr>
                    </table>
                    
                    <div class="rp-divider"></div>
                    
                    <table class="rp-table">
                        <tr>
                            <td colspan="2" class="text-start rp-item-name">Beras Raja Lele 5Kg</td>
                        </tr>
                        <tr>
                            <td class="text-start">1 x 65.000</td>
                            <td class="text-end fw-semibold text-black">65.000</td>
                        </tr>
                    </table>
                    
                    <div class="rp-divider"></div>
                    
                    <table class="rp-table">
                        <tr class="rp-total">
                            <td class="text-start pb-2">TOTAL</td>
                            <td class="text-end pb-2">Rp 65.000</td>
                        </tr>
                        <tr>
                            <td class="text-start pt-2">Tunai</td>
                            <td class="text-end pt-2">Rp 65.000</td>
                        </tr>
                        <tr>
                            <td class="text-start">Kembali</td>
                            <td class="text-end">Rp 0</td>
                        </tr>
                    </table>
                    
                    <div class="rp-divider"></div>
                    
                    <div class="rp-footer">
                        @if($hasPremiumStruk && $store->receipt_footer)
                            <div id="preview-footer" style="white-space: pre-wrap; margin-bottom: 5px;">{{ $store->receipt_footer }}</div>
                        @else
                            <div id="preview-footer" style="white-space: pre-wrap; margin-bottom: 5px;">Terima kasih atas kunjungannya!</div>
                            <div style="font-size: 8px; color: #555;">* Powered by Usavetra *</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Live preview
    document.getElementById('shop_name').addEventListener('input', function(e) {
        document.getElementById('preview-name').innerText = e.target.value || 'NAMA TOKO';
    });
    document.getElementById('address').addEventListener('input', function(e) {
        document.getElementById('preview-address').innerText = e.target.value || 'Alamat Toko';
    });
    document.getElementById('phone').addEventListener('input', function(e) {
        document.getElementById('preview-phone').innerText = 'Telp: ' + (e.target.value || '-');
    });
    document.getElementById('footer_text').addEventListener('input', function(e) {
        document.getElementById('preview-footer').innerText = e.target.value || 'Terima kasih';
    });
</script>
@endpush
