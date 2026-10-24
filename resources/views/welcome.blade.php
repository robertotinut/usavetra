<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usavetra - Modern UMKM POS</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        :root {
            --bg-color: #FAFAFA;
            --text-main: #111111;
            --text-muted: #555555;
            --primary: #2DD4BF; /* Teal */
            --primary-dark: #14B8A6;
            --border-color: rgba(0,0,0,0.08);
            --card-bg: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, .brand {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.04em;
        }

        /* Navbar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 48px;
            background: rgba(250, 250, 250, 0.9);
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            border-bottom: 1px solid transparent;
            transition: border-bottom 0.3s;
        }

        nav.scrolled {
            border-bottom: 1px solid var(--border-color);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text-main);
            text-decoration: none;
        }

        .brand i {
            color: var(--primary);
            font-size: 1.6rem;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-main);
            transition: opacity 0.2s;
        }

        .nav-links a:hover {
            opacity: 0.7;
        }

        .nav-icons {
            display: flex;
            gap: 12px;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid #E5E7EB;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .icon-btn:hover {
            background: #F3F4F6;
        }

        /* Hero Layout */
        .hero {
            display: flex;
            padding: 140px 48px 100px 48px;
            min-height: calc(100vh - 90px);
            gap: 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .hero-left {
            flex: 1;
            padding-top: 60px;
            max-width: 500px;
            position: relative;
        }

        .hero-right {
            flex: 1.2;
            display: flex;
            justify-content: flex-end;
        }

        /* Left Content */
        .hero-left h1 {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
        }

        .hero-left p {
            font-size: 1.1rem;
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 60px;
        }

        /* Floating Icons Cluster */
        .icon-cluster {
            position: relative;
            height: 250px;
            margin-top: 40px;
        }

        .float-icon {
            position: absolute;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.02);
            color: #111;
        }

        .fi-1 { width: 64px; height: 64px; font-size: 32px; top: -30px; left: 80px; }
        .fi-2 { width: 56px; height: 56px; font-size: 28px; top: 30px; left: 0px; }
        .fi-3 { width: 72px; height: 72px; font-size: 36px; top: 40px; left: 90px; }
        .fi-4 { width: 50px; height: 50px; font-size: 24px; top: 110px; left: 30px; }
        .fi-5 { width: 56px; height: 56px; font-size: 28px; top: 120px; left: 110px; }
        .fi-6 { width: 64px; height: 64px; font-size: 32px; top: 80px; left: 190px; }
        .fi-7 { width: 50px; height: 50px; font-size: 24px; top: 130px; left: 260px; }
        .fi-8 { width: 56px; height: 56px; font-size: 28px; top: 50px; left: 300px; }

        /* Right Folder UI */
        .folder-ui {
            width: 100%;
            max-width: 650px;
            background: white;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .folder-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 8px;
        }

        .folder-header h2 {
            font-size: 1.8rem;
            font-weight: 500;
        }

        .btn-create {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #F3F4F6;
            border: none;
            padding: 8px 16px;
            border-radius: 100px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            color: var(--text-main);
            text-decoration: none;
        }
        
        .btn-create:hover { background: #E5E7EB; }

        /* Black Tab */
        .folder-tab {
            background: #1A1A1A;
            color: white;
            font-size: 1.4rem;
            font-weight: 500;
            padding: 16px 24px;
            border-radius: 16px 16px 0 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            clip-path: polygon(0 0, 85% 0, 95% 100%, 0% 100%);
            margin-bottom: -1px;
            z-index: 2;
            position: relative;
        }

        /* Grid Content */
        .folder-content {
            background: #FFFFFF;
            border-radius: 0 0 16px 16px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            z-index: 1;
        }

        .grid-card {
            border-radius: 16px;
            aspect-ratio: 4/5;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 20px;
            color: white;
            overflow: hidden;
            position: relative;
            text-decoration: none;
            transition: transform 0.2s;
        }
        
        .grid-card:hover { transform: translateY(-4px); }

        .grid-card h3 {
            font-size: 1.5rem;
            margin-bottom: 4px;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .grid-card p {
            font-size: 0.9rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .c-1 { background: linear-gradient(180deg, #F97316, #EA580C); }
        .c-2 { background: linear-gradient(180deg, #10B981, #059669); }
        .c-3 { background: linear-gradient(180deg, #F59E0B, #D97706); }
        .c-4 { background: linear-gradient(180deg, #6366F1, #4F46E5); }

        .c-1::after, .c-2::after, .c-3::after, .c-4::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 60%);
            z-index: 1;
        }
        
        .card-icon {
            position: absolute;
            top: 50%; left: 50%; transform: translate(-50%, -50%);
            font-size: 8rem; opacity: 0.15; z-index: 0;
        }

        /* --- NEW SECTIONS --- */
        .section-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 100px 48px;
        }

        .section-title {
            font-size: 3rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 16px;
        }

        .section-subtitle {
            text-align: center;
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Bento Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-box {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature-box.large {
            grid-column: span 2;
        }

        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.06);
        }

        .fb-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 24px;
        }
        .bg-teal { background: #CCFBF1; color: #0F766E; }
        .bg-purple { background: #F3E8FF; color: #7E22CE; }
        .bg-rose { background: #FFE4E6; color: #BE123C; }
        .bg-blue { background: #DBEAFE; color: #1D4ED8; }

        .feature-box h3 {
            font-size: 1.4rem;
            margin-bottom: 12px;
        }

        .feature-box p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* Pricing Section */
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 32px;
            max-width: 900px;
            margin: 0 auto;
        }

        .price-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            position: relative;
        }

        .price-card.featured {
            border: 2px solid var(--text-main);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .price-header { margin-bottom: 32px; }
        .price-header h3 { font-size: 1.5rem; margin-bottom: 8px; }
        .price-header .price { font-size: 3.5rem; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: baseline; gap: 8px; }
        .price-header .price span { font-size: 1rem; color: var(--text-muted); font-weight: 500; font-family: 'Inter', sans-serif; }

        .price-features { list-style: none; margin-bottom: 40px; }
        .price-features li { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; font-weight: 500; }
        .price-features i { color: var(--primary); font-size: 1.2rem; }
        .price-features .x-icon { color: #EF4444; }

        .btn-price {
            display: block;
            width: 100%;
            text-align: center;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-outline-dark {
            border: 1px solid var(--border-color);
            color: var(--text-main);
        }
        .btn-outline-dark:hover { background: #F3F4F6; }

        .btn-dark {
            background: var(--text-main);
            color: white;
            border: 1px solid var(--text-main);
        }
        .btn-dark:hover { opacity: 0.9; }

        .badge-popular {
            position: absolute;
            top: -14px; left: 50%;
            transform: translateX(-50%);
            background: var(--text-main);
            color: white;
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Footer */
        footer {
            background: white;
            border-top: 1px solid var(--border-color);
            padding: 60px 48px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .footer-links {
            display: flex;
            gap: 24px;
        }
        
        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
        }

        @media (max-width: 992px) {
            .hero { flex-direction: column; padding: 120px 24px 40px; }
            nav { padding: 16px 24px; }
            .nav-links { display: none; }
            .hero-left h1 { font-size: 3rem; }
            .features-grid, .pricing-grid { grid-template-columns: 1fr; }
            .feature-box.large { grid-column: span 1; }
            .section-container { padding: 60px 24px; }
            .footer-content { flex-direction: column; gap: 24px; text-align: center; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav id="navbar">
        <a href="#" class="brand">
            <i class="ph-fill ph-storefront"></i> Usavetra
        </a>
        <div class="nav-links">
            <a href="#hero">Beranda</a>
            <a href="#fitur">Fitur POS</a>
            <a href="#harga">Harga</a>
            <a href="#solusi">Solusi UMKM</a>
        </div>
        <div class="nav-icons">
            <a href="{{ route('login') }}" class="icon-btn" title="Masuk">
                <i class="ph ph-user"></i>
            </a>
            @if(Route::has('register'))
            <a href="{{ route('register') }}" class="icon-btn" title="Daftar Gratis">
                <i class="ph ph-rocket-launch"></i>
            </a>
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <main id="hero" class="hero">
        <div class="hero-left">
            <h1>Manajemen Cerdas<br>Untuk Segala<br>Toko Anda.</h1>
            <p>Setiap transaksi menceritakan kisah bisnis Anda. Usavetra memungkinkan Anda melacak, menganalisis, dan mengembangkan usaha lewat satu layar interaktif.</p>
            
            <div class="icon-cluster">
                <div class="float-icon fi-1"><i class="ph ph-calculator"></i></div>
                <div class="float-icon fi-2"><i class="ph ph-wallet"></i></div>
                <div class="float-icon fi-3"><i class="ph ph-chart-line-up"></i></div>
                <div class="float-icon fi-4"><i class="ph ph-receipt"></i></div>
                <div class="float-icon fi-5"><i class="ph ph-users"></i></div>
                <div class="float-icon fi-6"><i class="ph ph-robot"></i></div>
                <div class="float-icon fi-7"><i class="ph ph-magic-wand"></i></div>
                <div class="float-icon fi-8"><i class="ph ph-cube"></i></div>
            </div>
        </div>

        <div class="hero-right">
            <div class="folder-ui">
                <div class="folder-header">
                    <h2>Bisnis</h2>
                    <a href="{{ route('register') }}" class="btn-create">
                        <i class="ph-bold ph-plus"></i> Mulai
                    </a>
                </div>
                
                <div class="folder-tab">
                    Fitur Utama
                </div>
                
                <div class="folder-content">
                    <a href="#fitur" class="grid-card c-1">
                        <i class="ph-fill ph-cash-register card-icon"></i>
                        <h3>Kasir Pintar</h3>
                        <p>Transaksi lancar & cetak struk</p>
                    </a>
                    <a href="#fitur" class="grid-card c-2">
                        <i class="ph-fill ph-address-book card-icon"></i>
                        <h3>Catat Hutang</h3>
                        <p>Tagih pelanggan via WhatsApp</p>
                    </a>
                    <a href="#fitur" class="grid-card c-3">
                        <i class="ph-fill ph-wallet card-icon"></i>
                        <h3>Buku Kas</h3>
                        <p>Arus uang operasional harian</p>
                    </a>
                    <a href="#fitur" class="grid-card c-4">
                        <i class="ph-fill ph-robot card-icon"></i>
                        <h3>Asisten AI</h3>
                        <p>Prediksi stok dan analisis HPP</p>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Features Section -->
    <section id="fitur" class="section-container">
        <div class="section-title">Semua Ada Di Sini.</div>
        <div class="section-subtitle">Tinggalkan pencatatan manual di buku tulis. Otomatiskan seluruh pembukuan dan kasir Anda menggunakan teknologi modern kami.</div>

        <div class="features-grid">
            <div class="feature-box large">
                <div class="fb-icon bg-teal"><i class="ph-fill ph-cash-register"></i></div>
                <h3>Sistem Kasir (POS) Kilat</h3>
                <p>Antarmuka penjualan yang dirancang untuk kecepatan tinggi. Proses pesanan, scan barcode, terapkan diskon, dan cetak struk hanya dalam hitungan detik. Tanpa antrean panjang, pelanggan makin senang.</p>
            </div>
            
            <div class="feature-box">
                <div class="fb-icon bg-purple"><i class="ph-fill ph-address-book"></i></div>
                <h3>Hutang & Piutang</h3>
                <p>Pelanggan sering ngebon? Catat otomatis, hitung sisa tagihan, dan kirim pengingat pelunasan langsung ke WhatsApp mereka hanya dengan satu klik.</p>
            </div>
            
            <div class="feature-box">
                <div class="fb-icon bg-blue"><i class="ph-fill ph-wallet"></i></div>
                <h3>Buku Kas Harian</h3>
                <p>Catat pengeluaran beli stok, bayar listrik, sampai gaji karyawan. Ketahui pasti kemana uang Anda mengalir setiap harinya.</p>
            </div>

            <div class="feature-box large">
                <div class="fb-icon bg-rose"><i class="ph-fill ph-robot"></i></div>
                <h3>Asisten Bisnis Cerdas (AI)</h3>
                <p>Layaknya punya konsultan pribadi. Sistem AI kami menganalisis kecepatan penjualan untuk memprediksi kapan stok akan habis, menghitung Harga Pokok Penjualan (HPP) ideal, dan mendeteksi kebocoran margin laba Anda.</p>
            </div>
        </div>
    </section>

    <!-- Solusi UMKM Section -->
    <section id="solusi" class="section-container" style="background: var(--bg-color);">
        <div class="section-title">Fleksibel Untuk Segala Usaha.</div>
        <div class="section-subtitle">Apapun jenis UMKM Anda, Usavetra dirancang agar mudah disesuaikan dengan kebutuhan spesifik operasional harian Anda.</div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px;">
            <div class="feature-box" style="display: flex; gap: 20px; align-items: flex-start;">
                <div class="fb-icon bg-blue" style="margin-bottom: 0; flex-shrink: 0;"><i class="ph-fill ph-storefront"></i></div>
                <div>
                    <h3 style="margin-bottom: 8px; font-size: 1.3rem;">Toko Kelontong & Retail</h3>
                    <p style="margin: 0;">Sistem kasir cepat mendukung ribuan produk dan pencarian via scanner barcode tanpa lag sedikitpun.</p>
                </div>
            </div>
            <div class="feature-box" style="display: flex; gap: 20px; align-items: flex-start;">
                <div class="fb-icon bg-teal" style="margin-bottom: 0; flex-shrink: 0;"><i class="ph-fill ph-coffee"></i></div>
                <div>
                    <h3 style="margin-bottom: 8px; font-size: 1.3rem;">Kafe & Kedai Kopi</h3>
                    <p style="margin: 0;">Pencatatan varian menu yang dinamis dan pemantauan pergerakan stok harian yang sangat akurat.</p>
                </div>
            </div>
            <div class="feature-box" style="display: flex; gap: 20px; align-items: flex-start;">
                <div class="fb-icon bg-rose" style="margin-bottom: 0; flex-shrink: 0;"><i class="ph-fill ph-t-shirt"></i></div>
                <div>
                    <h3 style="margin-bottom: 8px; font-size: 1.3rem;">Butik & Toko Pakaian</h3>
                    <p style="margin: 0;">Kelola data pelanggan loyal Anda dan cek ketersediaan barang di etalase secara real-time.</p>
                </div>
            </div>
            <div class="feature-box" style="display: flex; gap: 20px; align-items: flex-start;">
                <div class="fb-icon bg-purple" style="margin-bottom: 0; flex-shrink: 0;"><i class="ph-fill ph-wrench"></i></div>
                <div>
                    <h3 style="margin-bottom: 8px; font-size: 1.3rem;">Jasa & Bengkel</h3>
                    <p style="margin: 0;">Manajemen data pelanggan, histori transaksi, dan fitur pencatatan hutang/cicilan yang terintegrasi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="harga" class="section-container" style="background: white; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
        <div class="section-title">Harga Jujur. Tanpa Kejutan.</div>
        <div class="section-subtitle">Pilih paket yang paling pas untuk skala usaha Anda. Anda bisa mulai dari gratis.</div>

        <div class="pricing-grid">
            <!-- Free -->
            <div class="price-card">
                <div class="price-header">
                    <h3>Paket Perintis</h3>
                    <div class="price">Rp 0<span>/bulan</span></div>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Sempurna untuk yang baru merintis bisnis.</p>
                </div>
                <ul class="price-features">
                    <li><i class="ph-fill ph-check-circle"></i> 1 Akun Toko & Kasir</li>
                    <li><i class="ph-fill ph-check-circle"></i> Modul Kasir (POS) Dasar</li>
                    <li><i class="ph-fill ph-check-circle"></i> Manajemen Inventaris</li>
                    <li style="color: #999;"><i class="ph-fill ph-x-circle x-icon"></i> Fitur Hutang & Piutang</li>
                    <li style="color: #999;"><i class="ph-fill ph-x-circle x-icon"></i> Asisten Analitik AI</li>
                </ul>
                <a href="{{ route('register') }}" class="btn-price btn-outline-dark">Mulai Gratis</a>
            </div>

            <!-- Pro -->
            <div class="price-card featured">
                <div class="badge-popular">PALING LARIS</div>
                <div class="price-header">
                    <h3>Paket Juragan</h3>
                    <div class="price">Rp 55rb<span>/bulan</span></div>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Buka semua fitur profesional tanpa batas.</p>
                </div>
                <ul class="price-features">
                    <li><i class="ph-fill ph-check-circle"></i> Akun Kasir Tidak Terbatas</li>
                    <li><i class="ph-fill ph-check-circle"></i> Modul Kasir & Inventaris Lengkap</li>
                    <li><i class="ph-fill ph-check-circle"></i> Fitur Hutang Piutang + WA</li>
                    <li><i class="ph-fill ph-check-circle"></i> Asisten Bisnis AI (Prediksi Stok)</li>
                    <li><i class="ph-fill ph-check-circle"></i> Buku Kas & Laporan Finansial</li>
                </ul>
                <a href="{{ route('register') }}" class="btn-price btn-dark">Coba Gratis 14 Hari</a>
            </div>
        </div>
    </section>

    <!-- CTA WhatsApp Section -->
    <section class="section-container" style="padding-top: 20px; padding-bottom: 80px;">
        <div style="background: var(--text-main); border-radius: 24px; padding: 60px 40px; text-align: center; color: white; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
            <div style="position: relative; z-index: 2;">
                <h2 style="font-size: 2.2rem; margin-bottom: 16px; color: white;">Masih Ragu atau Ada Pertanyaan?</h2>
                <p style="font-size: 1.05rem; opacity: 0.9; margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                    Tim kami siap membantu mencarikan solusi terbaik untuk jenis usaha Anda. Jangan sungkan untuk berkonsultasi secara gratis dengan tim kami via WhatsApp.
                </p>
                <a href="https://wa.me/6281234567890?text=Halo%20tim%20Usavetra,%20saya%20mau%20tanya-tanya%20dulu%20nih%20seputar%20aplikasi%20ini." target="_blank" style="display: inline-flex; align-items: center; gap: 10px; background: #25D366; color: white; padding: 14px 32px; border-radius: 100px; font-weight: 700; text-decoration: none; font-size: 1.05rem; transition: all 0.2s;">
                    <i class="ph-fill ph-whatsapp-logo" style="font-size: 1.5rem;"></i> Ngobrol via WhatsApp
                </a>
            </div>
            <!-- Decorative circle -->
            <div style="position: absolute; width: 300px; height: 300px; background: rgba(45, 212, 191, 0.2); border-radius: 50%; top: -100px; right: -50px; filter: blur(50px);"></div>
            <div style="position: absolute; width: 200px; height: 200px; background: rgba(37, 211, 102, 0.2); border-radius: 50%; bottom: -50px; left: -50px; filter: blur(40px);"></div>
        </div>
    </section>

    <!-- Simple Footer -->
    <footer>
        <div class="footer-content">
            <div class="brand">
                <i class="ph-fill ph-storefront"></i> Usavetra
            </div>
            <div class="footer-links">
                <a href="#">Privasi</a>
                <a href="#">Syarat Layanan</a>
                <a href="#">Pusat Bantuan</a>
                <a href="#">Hubungi Kami</a>
            </div>
            <div style="color: var(--text-muted); font-size: 0.9rem;">
                &copy; {{ date('Y') }} Usavetra Inc. Indonesia.
            </div>
        </div>
    </footer>

    <!-- Scroll Script for Navbar -->
    <script>
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 20) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
