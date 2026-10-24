<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Register | Usavetra - SaaS UMKM</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <!-- preloader css -->
        <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" />
        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-xxl-3 col-lg-4 col-md-5">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100">
                                    <div class="mb-4 mb-md-5 text-center">
                                        <a href="{{ url('/') }}" class="d-block auth-logo">
                                            <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="" height="28"> <span class="logo-txt">Usavetra</span>
                                        </a>
                                    </div>
                                    <div class="auth-content my-auto">
                                        <div class="text-center">
                                            <h5 class="mb-0">Daftar Akun Baru</h5>
                                            <p class="text-muted mt-2">Mulai digitalisasi UMKM Anda sekarang.</p>
                                        </div>
                                        <form class="mt-4 pt-2" method="POST" action="{{ route('register') }}">
                                            @csrf
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Nama Toko / UMKM</label>
                                                <input type="text" class="form-control @error('store_name') is-invalid @enderror" name="store_name" placeholder="Misal: Toko Budi, Warkop Mantap" value="{{ old('store_name') }}" required autofocus>
                                                @error('store_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Nama Pemilik</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Masukkan nama Anda" value="{{ old('name') }}" required>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Masukkan email aktif" value="{{ old('email') }}" required>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Password</label>
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Minimal 8 karakter" required>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label">Konfirmasi Password</label>
                                                <input type="password" class="form-control" name="password_confirmation" placeholder="Ulangi password" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Daftar Sekarang</button>
                                            </div>
                                        </form>

                                        <div class="mt-4 text-center">
                                            <p class="text-muted mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-semibold"> Log In </a> </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Usavetra. Dibuat dengan <i class="mdi mdi-heart text-danger"></i> untuk UMKM Indonesia.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>
                    <!-- end col -->
                    <div class="col-xxl-9 col-lg-8 col-md-7">
                        <div class="auth-bg pt-md-5 p-4 d-flex" style="background-image: url('{{ asset('assets/images/login_bg_umkm.png') }}'); background-size: cover; background-position: center;">
                            <div class="bg-overlay" style="background-color: rgba(0, 0, 0, 0.35);"></div>
                            <ul class="bg-bubbles">
                                <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
                            </ul>
                            <!-- end bubble effect -->
                            <div class="row justify-content-center align-items-center">
                                <div class="col-xl-7">
                                    <div class="p-0 p-sm-4 px-xl-0">
                                        <div id="reviewcarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-indicators carousel-indicators-rounded justify-content-start ms-0 mb-0">
                                                <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                                <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                            </div>
                                            <!-- end carouselIndicators -->
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="testi-contain text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                        <h4 class="mt-4 fw-medium lh-base text-white">"Aplikasi ini sangat membantu saya mengetahui laba harian warung. Tidak perlu lagi bingung uang bercampur!"</h4>
                                                        <div class="mt-4 pt-3 pb-5">
                                                            <div class="d-flex align-items-start">
                                                                <div class="flex-grow-1 ms-3 mb-4">
                                                                    <h5 class="font-size-18 text-white">Bapak Budi</h5>
                                                                    <p class="mb-0 text-white-50">Pemilik Warung Madura</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="carousel-item">
                                                    <div class="testi-contain text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                        <h4 class="mt-4 fw-medium lh-base text-white">"Sekarang saya tahu persis mana barang yang laku keras dan mana yang harus didiskon. Usaha makin maju!"</h4>
                                                        <div class="mt-4 pt-3 pb-5">
                                                            <div class="d-flex align-items-start">
                                                                <div class="flex-grow-1 ms-3 mb-4">
                                                                    <h5 class="font-size-18 text-white">Ibu Siti</h5>
                                                                    <p class="mb-0 text-white-50">Pemilik Warung Kelontong</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="carousel-item">
                                                    <div class="testi-contain text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                        <h4 class="mt-4 fw-medium lh-base text-white">"Laporan hariannya luar biasa. Tinggal lihat HP, semua transaksi tercatat dengan rapi dan aman."</h4>
                                                        <div class="mt-4 pt-3 pb-5">
                                                            <div class="d-flex align-items-start">
                                                                <div class="flex-1 ms-3 mb-4">
                                                                    <h5 class="font-size-18 text-white">Andi</h5>
                                                                    <p class="mb-0 text-white-50">Pemilik Warkop</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
        <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script>
    </body>
</html>
