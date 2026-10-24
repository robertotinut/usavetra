@extends('layouts.app')
@section('title', 'Langganan & Fitur Premium')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Langganan & Fitur Premium</h4>
        </div>
    </div>
</div>

@if(session('upgrade_message'))
<div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
    <i class="mdi mdi-lock-outline me-2"></i> <strong>Akses Terbatas:</strong> {{ session('upgrade_message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title">Status Langganan Anda</h4>
                
                @if($isActive)
                    <div class="mt-4 mb-4">
                        <i class="mdi mdi-check-decagram text-success display-4"></i>
                        <h3 class="mt-3">Paket {{ $subscription->package->name }} Aktif</h3>
                        <p class="text-muted font-size-15">Berlaku sampai dengan <strong>{{ \Carbon\Carbon::parse($subscription->end_date)->translatedFormat('d F Y') }}</strong></p>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <h5 class="mb-3">Fitur yang dapat Anda nikmati:</h5>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                @foreach($subscription->package->features as $f)
                                    <span class="badge bg-soft-success text-success font-size-14 px-3 py-2">
                                        <i class="mdi mdi-check me-1"></i> {{ ucwords(str_replace('_', ' ', $f)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-4 mb-4">
                        <i class="mdi mdi-alert-decagram text-warning display-4"></i>
                        <h3 class="mt-3">Status: Gratis / Kadaluarsa</h3>
                        <p class="text-muted font-size-15">Saat ini Anda menggunakan fitur dasar. Tingkatkan paket Anda untuk menikmati layanan premium tanpa batas.</p>
                    </div>
                @endif
                
                <hr class="my-5">
                
                <h4 class="card-title mb-4">Pilihan Paket Premium</h4>
                <div class="row justify-content-center">
                    @foreach($packages as $pkg)
                        <div class="col-lg-4 col-md-6">
                            <div class="card border {{ ($isActive && $subscription->package_id == $pkg->id) ? 'border-primary' : '' }}">
                                <div class="card-body p-4 text-center">
                                    <h5 class="font-size-16 mb-2">{{ $pkg->name }}</h5>
                                    <h2 class="mb-4">Rp {{ number_format($pkg->price, 0, ',', '.') }}<span class="font-size-14 text-muted">/bln</span></h2>
                                    
                                    <ul class="list-unstyled text-start mb-4">
                                        @foreach($pkg->features as $f)
                                        <li class="mb-2"><i class="mdi mdi-check-circle text-success me-2"></i>{{ ucwords(str_replace('_', ' ', $f)) }}</li>
                                        @endforeach
                                    </ul>
                                    
                                    @if($isActive && $subscription->package_id == $pkg->id)
                                        <button class="btn btn-outline-primary w-100" disabled>Paket Anda Saat Ini</button>
                                    @else
                                        @php
                                            $waText = "Halo Admin, saya tertarik untuk melakukan upgrade SaaS UMKM ke " . $pkg->name . ".";
                                            if(auth()->user()) {
                                                $waText .= " (Toko: " . optional(\App\Models\Store::find(auth()->user()->store_id))->name . " | Email: " . auth()->user()->email . ")";
                                            }
                                        @endphp
                                        <a href="https://wa.me/6285854543488?text={{ urlencode($waText) }}" target="_blank" class="btn btn-primary w-100">Pilih Paket Ini</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
