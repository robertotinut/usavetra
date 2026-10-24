<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Buat Password Baru | Usavetra - SaaS UMKM</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
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
                                            <h5 class="mb-0">Buat Password Baru</h5>
                                            <p class="text-muted mt-2">Silakan atur kata sandi baru untuk akun Anda.</p>
                                        </div>

                                        <form class="mt-4 pt-2" method="POST" action="{{ route('password.update') }}">
                                            @csrf
                                            <input type="hidden" name="token" value="{{ $token }}">

                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Masukkan email" value="{{ $email ?? old('email') }}" required autocomplete="email" readonly>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Password Baru</label>
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" autofocus>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Konfirmasi Password Baru</label>
                                                <input type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                            </div>

                                            <div class="mb-3 mt-4">
                                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Reset Password</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Usavetra. Dibuat dengan <i class="mdi mdi-heart text-danger"></i> untuk UMKM Indonesia.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-xxl-9 col-lg-8 col-md-7">
                        <div class="auth-bg pt-md-5 p-4 d-flex" style="background-image: url('{{ asset('assets/images/login_bg_umkm.png') }}'); background-size: cover; background-position: center;">
                            <div class="bg-overlay" style="background-color: rgba(0, 0, 0, 0.35);"></div>
                            <ul class="bg-bubbles">
                                <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
                            </ul>
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
