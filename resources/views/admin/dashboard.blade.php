@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">SaaS Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">SaaS Admin</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Toko / UMKM</span>
                        <h4 class="mb-3"><span class="counter-value" data-target="{{ $totalStores }}">{{ $totalStores }}</span></h4>
                    </div>
                    <div class="col-6">
                        <i data-feather="users" class="text-primary" style="width: 48px; height: 48px; float: right;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Users</span>
                        <h4 class="mb-3"><span class="counter-value" data-target="{{ $totalUsers }}">{{ $totalUsers }}</span></h4>
                    </div>
                    <div class="col-6">
                        <i data-feather="user" class="text-success" style="width: 48px; height: 48px; float: right;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Paket SaaS</span>
                        <h4 class="mb-3"><span class="counter-value" data-target="{{ $totalPackages }}">{{ $totalPackages }}</span></h4>
                    </div>
                    <div class="col-6">
                        <i data-feather="package" class="text-warning" style="width: 48px; height: 48px; float: right;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Langganan Aktif</span>
                        <h4 class="mb-3"><span class="counter-value" data-target="{{ $activeSubscriptions }}">{{ $activeSubscriptions }}</span></h4>
                    </div>
                    <div class="col-6">
                        <i data-feather="check-circle" class="text-danger" style="width: 48px; height: 48px; float: right;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
