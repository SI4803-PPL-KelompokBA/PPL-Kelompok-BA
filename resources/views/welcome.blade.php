<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBAM Tracker</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
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
            background: #f5faf5;
            color: #173d29;
            padding: 20px;
        }

        .hero {
            position: relative;
            height: calc(100vh - 40px);
            min-height: 620px;
            border-radius: 36px;
            border: 4px solid #ffffff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #ffffff;
            background:
                linear-gradient(
                    rgba(14, 55, 31, 0.20),
                    rgba(14, 55, 31, 0.42)
                ),
                url("{{ asset('images/welcome-bg.jpg') }}")
                center / cover no-repeat;
        }

        .navbar {
            position: relative;
            z-index: 5;
            width: calc(100% - 80px);
            max-width: 1000px;
            margin-top: 28px;
            background: rgba(255, 255, 255, 0.90);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 999px;
            padding: 12px 18px 12px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 8px 30px rgba(21, 67, 37, 0.12);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 18px;
            color: #173d29;
            text-decoration: none;
        }

        .logo svg {
            width: 30px;
            height: 30px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-links a {
            color: #58715f;
            text-decoration: none;
            font-size: 13px;
            padding: 8px 14px;
            border-radius: 999px;
            transition: background 0.2s, color 0.2s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #24834b;
            background: #edf8ed;
        }

        .nav-links a.active {
            font-weight: 600;
        }

        .btn-contact {
            color: #ffffff;
            background: #24834b;
            border: 1px solid #24834b;
            padding: 9px 19px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-contact:hover {
            background: #176638;
            color: #ffffff;
        }

        .hero-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 24px 40px;
            max-width: 950px;
        }

        .hero-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1.5px;
            margin-bottom: 22px;
            padding: 10px 18px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.17);
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px);
        }

        .hero-content h1 {
            font-size: clamp(36px, 5.5vw, 64px);
            line-height: 1.12;
            font-weight: 800;
            letter-spacing: -1.8px;
            color: #ffffff;
            text-shadow: 0 2px 18px rgba(0, 0, 0, 0.25);
        }

        .hero-content h1 span {
            color: #d9ffd8;
        }

        .hero-content p {
            margin-top: 22px;
            font-size: 15px;
            max-width: 680px;
            line-height: 1.8;
            color: #ffffff;
            text-shadow: 0 1px 10px rgba(0, 0, 0, 0.3);
        }

        .buttons {
            margin-top: 34px;
            display: flex;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 16px 30px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s, background 0.2s;
        }

        .btn:hover {
            transform: translateY(-3px);
        }

        .btn-primary {
            background: #d9f5d5;
            color: #205a35;
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        .btn-primary:hover {
            background: #ffffff;
            color: #205a35;
        }

        .pill {
            position: absolute;
            bottom: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 22px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.23);
            border: 1px solid rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            color: #ffffff;
            font-size: 14px;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(20, 70, 35, 0.12);
        }

        .pill-left {
            left: 36px;
            padding: 10px 22px 10px 12px;
        }

        .pill-right {
            right: 36px;
            transition: background 0.2s;
        }

        .pill-right:hover {
            background: rgba(255, 255, 255, 0.38);
            color: #ffffff;
        }

        .avatars {
            display: flex;
        }

        .avatars span {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.9);
            margin-left: -10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #ffffff;
        }

        .avatars span:first-child {
            margin-left: 0;
        }

        .avatars span:nth-child(1) {
            background: #39865b;
        }

        .avatars span:nth-child(2) {
            background: #70a9a0;
        }

        .avatars span:nth-child(3) {
            background: #a6bd70;
        }

        .section-label {
            color: #24834b;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .about {
            max-width: 900px;
            margin: 0 auto;
            padding: 95px 24px 75px;
            text-align: center;
        }

        .about h2,
        .section-heading h2 {
            font-size: clamp(27px, 4vw, 36px);
            letter-spacing: -0.8px;
            margin-bottom: 18px;
            color: #206b3d;
        }

        .about p {
            color: #64776a;
            line-height: 1.9;
            font-size: 14px;
        }

        .features {
            max-width: 1100px;
            margin: 0 auto;
            padding: 35px 15px 90px;
        }

        .section-heading {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 42px;
        }

        .section-heading p {
            color: #718078;
            font-size: 14px;
            line-height: 1.8;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .feature-card {
            background: #ffffff;
            padding: 28px 22px;
            border-radius: 20px;
            border: 1px solid #e7f0e7;
            box-shadow: 0 8px 25px rgba(23, 61, 41, 0.04);
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 30px rgba(23, 61, 41, 0.09);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            background: #eaf5e9;
            color: #24834b;
            font-size: 25px;
            margin-bottom: 22px;
        }

        .feature-card h3 {
            font-size: 16px;
            color: #173d29;
            margin-bottom: 12px;
        }

        .feature-card p {
            font-size: 12px;
            line-height: 1.8;
            color: #718078;
        }

        .workflow {
            background: #edf6ed;
            padding: 90px 24px;
        }

        .workflow-inner {
            max-width: 1050px;
            margin: 0 auto;
        }

        .workflow-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
            margin-top: 42px;
        }

        .workflow-card {
            background: #ffffff;
            padding: 30px 25px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(23, 61, 41, 0.04);
        }

        .workflow-number {
            width: 42px;
            height: 42px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #dff2df;
            color: #24834b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .workflow-card h3 {
            font-size: 16px;
            margin-bottom: 12px;
            color: #206b3d;
        }

        .workflow-card p {
            color: #718078;
            font-size: 12px;
            line-height: 1.8;
        }

        .sustainability {
            max-width: 1100px;
            margin: 85px auto;
            padding: 55px 30px;
            border-radius: 28px;
            background: linear-gradient(135deg, #173d29, #286b43);
            color: #ffffff;
            text-align: center;
        }

        .sustainability h2 {
            font-size: clamp(25px, 4vw, 36px);
            margin-bottom: 18px;
        }

        .sustainability p {
            max-width: 700px;
            margin: 0 auto;
            color: #d7ebdd;
            font-size: 14px;
            line-height: 1.9;
        }

        .footer {
            text-align: center;
            padding: 0 15px 30px;
            font-size: 12px;
            color: #819185;
        }

        @media (max-width: 900px) {
            .feature-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 800px) {
            body {
                padding: 10px;
            }

            .hero {
                height: calc(100vh - 20px);
                min-height: 650px;
                border-radius: 24px;
            }

            .nav-links {
                display: none;
            }

            .navbar {
                width: calc(100% - 32px);
                padding: 12px 15px;
            }

            .logo {
                font-size: 15px;
            }

            .btn-contact {
                padding: 8px 12px;
                font-size: 11px;
            }

            .hero-content {
                padding: 0 22px 50px;
            }

            .hero-content h1 {
                font-size: clamp(34px, 8vw, 48px);
                letter-spacing: -1px;
            }

            .hero-content p {
                font-size: 13px;
            }

            .pill-left {
                left: 16px;
                bottom: 18px;
            }

            .pill-right {
                display: none;
            }

            .workflow-grid {
                grid-template-columns: 1fr;
            }

            .about {
                padding: 70px 20px;
            }
        }

        @media (max-width: 520px) {
            .feature-grid {
                grid-template-columns: 1fr;
            }

            .hero-content h1 {
                font-size: 33px;
            }

            .hero-label {
                font-size: 10px;
            }

            .pill {
                font-size: 12px;
                padding: 9px 14px;
            }

            .sustainability {
                margin: 55px auto;
                padding: 40px 22px;
            }
        }
    </style>
</head>

<body>

    <section class="hero" id="home">

        <nav class="navbar">

            <a href="#home" class="logo">
                <svg viewBox="0 0 32 32" fill="none">
                    <path d="M6 26C6 14 14 6 26 6c0 12-8 20-20 20Z" fill="#24834b"/>
                    <path d="M6 26 18 14" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                </svg>
                CBAM Tracker
            </a>

            <div class="nav-links">
                <a href="#home" class="active">Home</a>
                <a href="#about">Tentang</a>
                <a href="#features">Fitur</a>
                <a href="#workflow">Alur Sistem</a>
            </div>

            <a href="{{ route('login') }}" class="btn-contact">
                Sign In →
            </a>

        </nav>

        <div class="hero-content">

            <div class="hero-label">
                <i class="bi bi-leaf-fill"></i>
                CARBON EMISSION TRACKING SYSTEM
            </div>

            <h1>
                Track Carbon, Grow Greener.
                <br>
                <span>Building a Sustainable Future</span>
            </h1>

            <p>
                Monitor, calculate, and report carbon emissions across agricultural
                operations through an integrated and transparent sustainability platform.
            </p>

            <div class="buttons">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    Get Started →
                </a>
            </div>

        </div>

        <div class="pill pill-left">
            <div class="avatars">
                <span>C</span>
                <span>B</span>
                <span>A</span>
                <span>M</span>
            </div>
        </div>

        <a href="#about" class="pill pill-right">
            Scroll Down ↓
        </a>

    </section>

    <section class="about" id="about">
        <div class="section-label">Tentang Kami</div>

        <h2>Mengenali Emisi, Mewujudkan Masa Depan yang Lebih Hijau</h2>

        <p>
            CBAM Tracker merupakan sistem informasi untuk mencatat, menghitung,
            memantau, dan melaporkan emisi gas rumah kaca dari aktivitas operasional
            perkebunan kelapa sawit. Sistem ini membantu perusahaan meningkatkan
            transparansi data emisi, mendukung pengelolaan keberlanjutan, serta
            mempersiapkan dokumentasi lingkungan untuk kebutuhan rantai pasok
            dan perdagangan internasional.
        </p>
    </section>

    <section class="features" id="features">

        <div class="section-heading">
            <div class="section-label">Fitur Utama</div>
            <h2>Solusi Terintegrasi untuk Pengelolaan Emisi Karbon</h2>
            <p>
                Berbagai fitur untuk mencatat aktivitas operasional, menghitung emisi,
                memantau kinerja karbon, dan menyusun laporan keberlanjutan.
            </p>
        </div>

        <div class="feature-grid">

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-fuel-pump"></i>
                </div>
                <h3>Pencatatan Emisi Operasional</h3>
                <p>
                    Pencatatan penggunaan bahan bakar alat berat, pupuk kimia,
                    konsumsi listrik, dan aktivitas operasional perkebunan.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-calculator"></i>
                </div>
                <h3>Perhitungan Emisi</h3>
                <p>
                    Konversi data aktivitas operasional menjadi nilai emisi gas
                    rumah kaca berdasarkan faktor emisi yang ditetapkan.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h3>Pemantauan Karbon</h3>
                <p>
                    Pemantauan jumlah emisi, tren karbon, dan pencapaian target
                    penurunan emisi perusahaan.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <h3>Laporan</h3>
                <p>
                    Penyusunan laporan emisi dan dokumentasi operasional untuk
                    mendukung proses validasi dan audit.
                </p>
            </div>

        </div>

    </section>

    <section class="workflow" id="workflow">

        <div class="workflow-inner">

            <div class="section-heading">
                <div class="section-label">Alur Sistem</div>
                <h2>Bagaimana CBAM Tracker Bekerja?</h2>
                <p>
                    Tiga peran utama bekerja sama untuk memastikan pencatatan,
                    perhitungan, dan pelaporan emisi karbon berjalan secara
                    akurat dan dapat dipertanggungjawabkan.
                </p>
            </div>

            <div class="workflow-grid">

                <div class="workflow-card">
                    <div class="workflow-number">01</div>
                    <h3>Data Inputter</h3>
                    <p>
                        Memasukkan data aktivitas operasional harian perkebunan,
                        seperti penggunaan pupuk, bahan bakar, listrik,
                        dan pengolahan lahan.
                    </p>
                </div>

                <div class="workflow-card">
                    <div class="workflow-number">02</div>
                    <h3>ESG Officer</h3>
                    <p>
                        Mengelola faktor emisi, melakukan perhitungan emisi,
                        memantau kinerja karbon, dan mengatur target
                        penurunan emisi perusahaan.
                    </p>
                </div>

                <div class="workflow-card">
                    <div class="workflow-number">03</div>
                    <h3>Auditor / Board of Directors</h3>
                    <p>
                        Memeriksa dan memvalidasi hasil perhitungan serta
                        laporan akhir jejak karbon untuk mendukung
                        akuntabilitas perusahaan.
                    </p>
                </div>

            </div>

        </div>

    </section>

    <section class="sustainability">

        <div class="section-label" style="color: #b8e6b9;">
            Our Commitment
        </div>

        <h2>Towards a Greener Future.</h2>

        <p>
            Supporting transparent carbon accounting and responsible agricultural
            supply chains through accurate data, reliable reporting, and sustainable
            operational practices.
        </p>

    </section>

    <div class="footer">
        © {{ date('Y') }} CBAM Tracker. Supporting a greener future.
    </div>

</body>
</html>