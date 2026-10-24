<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title', 'Dashboard') | Usavetra - SaaS UMKM</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
        <meta name="theme-color" content="#0ca678">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- plugin css -->
        <link href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />

        <!-- preloader css -->
        <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- DataTables Css -->
        <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
        <!-- Mobile Native App Css -->
        <link href="{{ asset('assets/css/mobile-app.css') }}" rel="stylesheet" type="text/css" />

        @stack('css')
    </head>

    <body>
        <!-- Begin page -->
        <div id="layout-wrapper">
            
            <!-- Topbar Start -->
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="{{ url('/') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="" height="24">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="" height="24"> <span class="logo-txt">Usavetra</span>
                                </span>
                            </a>

                            <a href="{{ url('/') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="" height="24">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="" height="24"> <span class="logo-txt">Usavetra</span>
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>

                        <!-- Nama Toko -->
                        <div class="d-flex align-items-center ms-3">
                            <h4 class="mb-0 font-size-16 text-primary fw-bold">
                                @php
                                    $topbarProfile = \App\Models\Profile::first();
                                    echo $topbarProfile ? $topbarProfile->shop_name : 'Usavetra';
                                @endphp
                            </h4>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="dropdown d-none d-sm-inline-block">
                            <button type="button" class="btn header-item" id="mode-setting-btn">
                                <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                                <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                            </button>
                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i data-feather="bell" class="icon-lg"></i>
                                @php
                                    $hasNotification = false;
                                    if(auth()->check() && auth()->user()->subscription) {
                                        $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse(auth()->user()->subscription->end_date), false);
                                        if($daysLeft <= 7) $hasNotification = true;
                                    }
                                @endphp
                                @if($hasNotification)
                                <span class="badge bg-danger rounded-pill">1</span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0"> Notifikasi </h6>
                                        </div>
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 230px;">
                                    @if($hasNotification)
                                    <a href="{{ route('upgrade') }}" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 avatar-sm me-3">
                                                <span class="avatar-title bg-warning rounded-circle font-size-16">
                                                    <i class="mdi mdi-alert-outline"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">Peringatan Masa Aktif</h6>
                                                <div class="font-size-13 text-muted">
                                                    <p class="mb-1">Paket Premium Anda akan habis dalam {{ $daysLeft < 0 ? 0 : $daysLeft }} hari. Segera perpanjang!</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    @else
                                    <div class="p-3 text-center text-muted">
                                        Belum ada notifikasi baru.
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::user()->name ?? 'User' }}</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('account.profile') }}"><i class="mdi mdi-face-man font-size-16 align-middle me-1"></i> Akun Saya</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Topbar End -->

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">
                <div data-simplebar class="h-100">
                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title" data-key="t-menu">Menu Utama</li>

                            <li>
                                <a href="{{ route('home') }}">
                                    <i data-feather="home"></i>
                                    <span data-key="t-dashboard">Dashboard</span>
                                </a>
                            </li>

                            @if(auth()->check() && auth()->user()->role === 'super_admin')
                            <li class="menu-title mt-2" data-key="t-saas">SaaS Admin</li>
                            
                            <li>
                                <a href="{{ route('admin.dashboard') }}">
                                    <i data-feather="monitor"></i>
                                    <span data-key="t-saas-dashboard">Admin Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.tenants.index') }}">
                                    <i data-feather="briefcase"></i>
                                    <span data-key="t-tenant">Pelanggan / Toko</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index') }}">
                                    <i data-feather="users"></i>
                                    <span data-key="t-users">Manajemen Pengguna</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.activities.index') }}">
                                    <i data-feather="activity"></i>
                                    <span data-key="t-activities">Monitoring Aktivitas</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.packages.index') }}">
                                    <i data-feather="package"></i>
                                    <span data-key="t-paket">Kelola Paket</span>
                                </a>
                            </li>
                            @endif

                            @if(auth()->check() && auth()->user()->role !== 'super_admin')


                            <li class="menu-title mt-2" data-key="t-components">Operasional</li>

                            <li>
                                <a href="{{ route('sales.create') }}">
                                    <i data-feather="shopping-cart"></i>
                                    <span data-key="t-kasir">Kasir (POS)</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="{{ route('sales.index') }}">
                                    <i data-feather="file-text"></i>
                                    <span data-key="t-riwayat-transaksi">Riwayat Transaksi</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="box"></i>
                                    <span data-key="t-master">Master Data</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('categories.index') }}" data-key="t-kategori">Kategori</a></li>
                                    @if(auth()->user()->role !== 'kasir')
                                    <li><a href="{{ route('suppliers.index') }}" data-key="t-supplier">Supplier</a></li>
                                    @endif
                                    <li><a href="{{ route('products.index') }}" data-key="t-produk">Produk</a></li>
                                </ul>
                            </li>

                            @if(auth()->user()->role !== 'kasir')
                            <li>
                                <a href="{{ route('purchases.index') }}">
                                    <i data-feather="truck"></i>
                                    <span data-key="t-kulakan">Kulakan / Beli</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('employees.index') }}">
                                    <i data-feather="users"></i>
                                    <span data-key="t-karyawan">Karyawan (Kasir)</span>
                                    @if(auth()->user()->role !== 'super_admin' && (!auth()->user()->subscription || !in_array('manajemen_karyawan', auth()->user()->subscription->package->features ?? [])))
                                        <span class="badge rounded-pill bg-warning float-end">PRO</span>
                                    @endif
                                </a>
                            </li>

                            <li class="menu-title mt-2" data-key="t-components">Bisnis & Laporan</li>
                            
                            @if(auth()->user()->role !== 'kasir')
                            <li>
                                <a href="{{ route('smart-assistant.index') }}">
                                    <i data-feather="cpu"></i>
                                    <span data-key="t-asisten">Asisten AI <span class="badge bg-danger ms-1">NEW</span></span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('calculator.hpp') }}">
                                    <i data-feather="monitor"></i>
                                    <span data-key="t-kalkulator">Kalkulator Bisnis</span>
                                </a>
                            </li>
                            @endif

                            <li class="menu-title mt-2" data-key="t-keuangan">Keuangan</li>

                            <li>
                                <a href="{{ route('cashflow.index') }}">
                                    <i data-feather="dollar-sign"></i>
                                    <span data-key="t-bukukas">Buku Kas</span>
                                </a>
                            </li>
                            @if(auth()->user()->role !== 'kasir')
                            <li>
                                <a href="{{ route('debts.index') }}">
                                    <i data-feather="book-open"></i>
                                    <span data-key="t-hutang">Hutang & Piutang</span>
                                </a>
                            </li>
                            @endif

                            <li>
                                <a href="{{ route('reports.index') }}">
                                    <i data-feather="pie-chart"></i>
                                    <span data-key="t-laporan">Laporan Keuangan</span>
                                </a>
                            </li>

                            <li class="menu-title mt-2" data-key="t-settings">Pengaturan</li>
                            
                            <li>
                                <a href="{{ route('profiles.index') }}">
                                    <i data-feather="settings"></i>
                                    <span data-key="t-pengaturan">Pengaturan Profil</span>
                                </a>
                            </li>

                            <li class="menu-title mt-2" data-key="t-langganan">Status Berlangganan</li>
                            <li>
                                <a href="{{ route('upgrade') }}">
                                    <i data-feather="credit-card"></i>
                                    <span data-key="t-status">Langganan & Premium</span>
                                </a>
                            </li>
                            @endif
                            @endif
                        </ul>

                        @if(auth()->user()->role !== 'kasir')
                        <div class="card sidebar-alert border-0 text-center mx-4 mb-0 mt-5">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/giftbox.png') }}" alt="">
                                <div class="mt-4">
                                    <h5 class="alertcard-title font-size-16">Fitur Premium</h5>
                                    <p class="font-size-13">Buka semua batas, nikmati akses penuh.</p>
                                    <a href="{{ route('upgrade') }}" class="btn btn-primary mt-2">Upgrade Now</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        @if(session('premium_error'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong><i class="mdi mdi-lock me-2"></i> Akses Terbatas!</strong> {{ session('premium_error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © Usavetra.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Dibuat untuk UMKM Indonesia
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- ============================================================== -->
        <!-- MOBILE BOTTOM NAVIGATION (Native App Feel) -->
        <!-- ============================================================== -->
        <nav class="mobile-bottom-nav">
            <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="bx bx-home-alt"></i>
                <span>Beranda</span>
            </a>
            
            <a href="{{ route('sales.index') }}" class="mobile-nav-item {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                <i class="bx bx-history"></i>
                <span>Riwayat</span>
            </a>

            <!-- Highlighted Center POS Button -->
            <a href="{{ route('sales.create') }}" class="mobile-nav-item pos-center-btn {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                <div class="pos-btn-inner">
                    <i class="bx bx-cart-alt"></i>
                </div>
                <span>Kasir</span>
            </a>

            <a href="{{ route('products.index') }}" class="mobile-nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="bx bx-package"></i>
                <span>Produk</span>
            </a>

            <a href="javascript:void(0);" class="mobile-nav-item" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas" aria-controls="mobileMenuOffcanvas">
                <i class="bx bx-grid-alt"></i>
                <span>Menu</span>
            </a>
        </nav>

        <!-- ============================================================== -->
        <!-- MOBILE OFFCANVAS DRAWER (Menu Lainnya) -->
        <!-- ============================================================== -->
        <div class="offcanvas offcanvas-bottom mobile-drawer" tabindex="-1" id="mobileMenuOffcanvas" style="height: 75vh;">
            <div class="offcanvas-header mobile-drawer-header">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-3">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-18">
                            <i class="mdi mdi-store-outline"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="offcanvas-title mb-0 font-size-15 fw-bold">
                            @php
                                $mProfile = \App\Models\Profile::first();
                                echo $mProfile ? $mProfile->shop_name : 'Usavetra';
                            @endphp
                        </h5>
                        <span class="font-size-12 text-muted">{{ Auth::user()->name ?? 'Pengguna' }} ({{ ucfirst(Auth::user()->role ?? 'Kasir') }})</span>
                    </div>
                </div>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-2" style="overflow-y: auto;">
                <div class="mobile-menu-grid">
                    @if(auth()->check() && auth()->user()->role === 'super_admin')
                    <a href="{{ route('admin.dashboard') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-danger-subtle text-danger"><i class="mdi mdi-monitor-dashboard"></i></div>
                        <span>Admin SaaS</span>
                    </a>
                    <a href="{{ route('admin.tenants.index') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-info-subtle text-info"><i class="mdi mdi-domain"></i></div>
                        <span>Toko UMKM</span>
                    </a>
                    <a href="{{ route('admin.packages.index') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-warning-subtle text-warning"><i class="mdi mdi-package-variant"></i></div>
                        <span>Paket SaaS</span>
                    </a>
                    <a href="{{ route('admin.activities.index') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-success-subtle text-success"><i class="mdi mdi-pulse"></i></div>
                        <span>Aktivitas</span>
                    </a>
                    @endif

                    @if(auth()->check() && auth()->user()->role !== 'super_admin')
                    <a href="{{ route('cashflow.index') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-success-subtle text-success"><i class="mdi mdi-cash-multiple"></i></div>
                        <span>Buku Kas</span>
                    </a>

                    @if(auth()->user()->role !== 'kasir')
                    <a href="{{ route('purchases.index') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-primary-subtle text-primary"><i class="mdi mdi-truck-delivery-outline"></i></div>
                        <span>Kulakan</span>
                    </a>

                    <a href="{{ route('debts.index') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-warning-subtle text-warning"><i class="mdi mdi-book-open-outline"></i></div>
                        <span>Hutang/Piutang</span>
                    </a>

                    <a href="{{ route('reports.index') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-info-subtle text-info"><i class="mdi mdi-chart-pie"></i></div>
                        <span>Laporan</span>
                    </a>

                    <a href="{{ route('categories.index') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-secondary-subtle text-secondary"><i class="mdi mdi-shape-outline"></i></div>
                        <span>Kategori</span>
                    </a>

                    <a href="{{ route('suppliers.index') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-dark-subtle text-dark"><i class="mdi mdi-account-group-outline"></i></div>
                        <span>Supplier</span>
                    </a>

                    <a href="{{ route('employees.index') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-purple-subtle text-purple" style="background: rgba(111, 66, 193, 0.1); color: #6f42c1;"><i class="mdi mdi-badge-account-outline"></i></div>
                        <span>Karyawan</span>
                    </a>

                    <a href="{{ route('smart-assistant.index') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-danger-subtle text-danger"><i class="mdi mdi-robot"></i></div>
                        <span>Asisten AI</span>
                    </a>

                    <a href="{{ route('calculator.hpp') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-success-subtle text-success"><i class="mdi mdi-calculator-variant-outline"></i></div>
                        <span>Kalkulator</span>
                    </a>
                    @endif
                    @endif

                    <a href="{{ route('account.profile') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-info-subtle text-info"><i class="mdi mdi-account-cog-outline"></i></div>
                        <span>Akun Saya</span>
                    </a>

                    @if(auth()->check() && auth()->user()->role !== 'kasir')
                    <a href="{{ route('profiles.index') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-primary-subtle text-primary"><i class="mdi mdi-cog-outline"></i></div>
                        <span>Pengaturan Toko</span>
                    </a>

                    <a href="{{ route('upgrade') }}" class="mobile-menu-card">
                        <div class="menu-icon-box bg-warning-subtle text-warning"><i class="mdi mdi-crown-outline"></i></div>
                        <span>Langganan</span>
                    </a>
                    @endif

                    <a href="{{ route('logout') }}" class="mobile-menu-card" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <div class="menu-icon-box bg-danger-subtle text-danger"><i class="mdi mdi-logout-variant"></i></div>
                        <span>Keluar</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
        <!-- pace js -->
        <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script>
        
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

        @stack('scripts')
        <script src="{{ asset('assets/js/app.js') }}"></script>
        
        <script>
            $(document).ready(function() {
                // Pastikan feather icons dirender
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
                
                // Inisialisasi DataTable Global
                if($('.datatable').length > 0) {
                    $('.datatable').DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                        },
                        "drawCallback": function() {
                            if (typeof feather !== 'undefined') {
                                feather.replace(); // re-render ikon feather jika ada pagination
                            }
                        }
                    });
                }
            });
        </script>

    </body>
</html>
