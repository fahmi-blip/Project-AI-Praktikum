<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DiagnoCare — Deteksi Risiko Diabetes Lebih Awal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Serif+Display:ital@0;1&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #F4F1EC;
            --bg2: #EDE9E2;
            --surface: #FFFFFF;
            --border: rgba(0, 0, 0, 0.1);
            --border2: rgba(0, 0, 0, 0.06);
            --text: #1A1A1A;
            --text2: #5C5A56;
            --text3: #9A9790;
            --accent: #2B5D4F;
            --accent2: #3D7A69;
            --accent-light: #E8F0EE;
            --green: #2B5D4F;
            --radius: 16px;
            --radius-sm: 10px;
            --mono: 'JetBrains Mono', monospace;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        /* ── NAVBAR ── */
        nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(244, 241, 236, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border2);
        }

        .nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
        }

        .nav-logo {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-logo svg {
            width: 18px;
            height: 18px;
            stroke: white;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .nav-title {
            font-family: 'DM Serif Display', serif;
            font-size: 18px;
        }

        .nav-badge {
            font-size: 11px;
            font-family: var(--mono);
            background: var(--accent-light);
            color: var(--accent);
            padding: 3px 10px;
            border-radius: 20px;
            border: 1px solid rgba(43, 93, 79, 0.2);
        }

        .btn-nav {
            padding: 8px 18px;
            background: var(--accent);
            color: white;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s;
        }

        .btn-nav:hover {
            background: var(--accent2);
        }

        /* ── HERO ── */
        .hero {
            max-width: 780px;
            margin: 0 auto;
            padding: 6rem 2rem 4rem;
            text-align: center;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-family: var(--mono);
            color: var(--text2);
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 5px 14px;
            margin-bottom: 1.5rem;
        }

        .hero-tag span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent);
            display: inline-block;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .4;
            }
        }

        h1 {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(2.2rem, 6vw, 3.5rem);
            line-height: 1.1;
            letter-spacing: -1.5px;
            margin-bottom: 1.25rem;
        }

        h1 em {
            font-style: italic;
            color: var(--accent);
        }

        .hero-desc {
            font-size: 17px;
            color: var(--text2);
            max-width: 520px;
            margin: 0 auto 2.5rem;
        }

        .hero-cta {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            padding: 14px 28px;
            background: var(--accent);
            color: white;
            border-radius: var(--radius-sm);
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s, transform .1s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: var(--accent2);
            transform: translateY(-1px);
        }

        .btn-primary svg {
            width: 18px;
            height: 18px;
            stroke: white;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .btn-outline {
            padding: 14px 28px;
            background: transparent;
            color: var(--text2);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            transition: background .2s;
        }

        .btn-outline:hover {
            background: var(--bg2);
        }

        .hero-note {
            font-size: 12px;
            font-family: var(--mono);
            color: var(--text3);
            margin-top: 1rem;
        }

        /* ── STATS BAR ── */
        .stats-bar {
            max-width: 1000px;
            margin: 0 auto 5rem;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            text-align: center;
        }

        .stat-num {
            font-family: 'DM Serif Display', serif;
            font-size: 42px;
            color: var(--accent);
            letter-spacing: -2px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text2);
            margin-top: 4px;
        }

        /* ── SECTION ── */
        section {
            max-width: 1000px;
            margin: 0 auto 5rem;
            padding: 0 2rem;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-family: var(--mono);
            color: var(--accent);
            background: var(--accent-light);
            border: 1px solid rgba(43, 93, 79, .2);
            border-radius: 20px;
            padding: 4px 12px;
            margin-bottom: 1rem;
        }

        h2 {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(1.6rem, 4vw, 2.2rem);
            letter-spacing: -0.5px;
            margin-bottom: 0.75rem;
        }

        h2 em {
            font-style: italic;
            color: var(--accent);
        }

        .section-desc {
            font-size: 15px;
            color: var(--text2);
            max-width: 520px;
            margin-bottom: 2.5rem;
        }

        /* ── FITUR ── */
        .fitur-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        @media (max-width: 700px) {
            .fitur-grid {
                grid-template-columns: 1fr;
            }
        }

        .fitur-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            transition: transform .2s, box-shadow .2s;
        }

        .fitur-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
        }

        .fitur-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--accent-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .fitur-icon svg {
            width: 22px;
            height: 22px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .fitur-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .fitur-desc {
            font-size: 13px;
            color: var(--text2);
            line-height: 1.6;
        }

        /* ── CARA KERJA ── */
        .steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        @media (max-width: 700px) {
            .steps {
                grid-template-columns: 1fr 1fr;
            }
        }

        .step-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem;
            position: relative;
        }

        .step-num {
            font-family: 'DM Serif Display', serif;
            font-size: 32px;
            color: var(--border);
            letter-spacing: -1px;
            margin-bottom: 8px;
        }

        .step-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .step-desc {
            font-size: 12px;
            color: var(--text2);
            line-height: 1.6;
        }

        .step-tag {
            display: inline-block;
            font-size: 10px;
            font-family: var(--mono);
            background: var(--accent-light);
            color: var(--accent);
            padding: 2px 8px;
            border-radius: 20px;
            margin-bottom: 8px;
        }

        /* ── INPUT PREVIEW ── */
        .input-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 700px) {
            .input-grid {
                grid-template-columns: 1fr;
            }
        }

        .input-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .input-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--accent-light);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .input-icon svg {
            width: 18px;
            height: 18px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .input-title {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .input-desc {
            font-size: 12px;
            color: var(--text2);
        }

        /* ── CTA BOTTOM ── */
        .cta-bottom {
            background: var(--accent);
            border-radius: var(--radius);
            padding: 3rem 2rem;
            text-align: center;
            margin: 0 2rem 5rem;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-bottom h2 {
            color: white;
            margin-bottom: 0.75rem;
        }

        .cta-bottom p {
            color: rgba(255, 255, 255, .7);
            font-size: 15px;
            margin-bottom: 2rem;
        }

        .btn-white {
            padding: 14px 28px;
            background: white;
            color: var(--accent);
            border-radius: var(--radius-sm);
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: opacity .2s, transform .1s;
        }

        .btn-white:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        .btn-white svg {
            width: 18px;
            height: 18px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        /* ── FOOTER ── */
        footer {
            border-top: 1px solid var(--border2);
            padding: 1.5rem 2rem;
            text-align: center;
            font-size: 11px;
            font-family: var(--mono);
            color: var(--text3);
        }

        @media (max-width: 600px) {
            .stats-bar {
                grid-template-columns: 1fr;
            }

            .nav-badge {
                display: none;
            }
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}
    <nav>
        <div class="nav-inner">
            <a href="/" class="nav-brand">
                <div class="nav-logo">
                    <svg viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                </div>
                <span class="nav-title">DiagnoCare</span>
            </a>
            <span class="nav-badge">Fuzzy Mamdani</span>
            <a href="{{ route('diagnosis.create') }}" class="btn-nav">Mulai Cek →</a>
        </div>
    </nav>

    {{-- HERO --}}
    <div class="hero">
        <div class="hero-tag"><span></span> Sistem Pakar Kecerdasan Buatan</div>
        <h1>Deteksi risiko <em>diabetes</em><br>tanpa alat medis</h1>
        <p class="hero-desc">Cukup jawab beberapa pertanyaan seputar kondisi dan kebiasaan sehari-hari. DiagnoCare akan menganalisis risiko diabetes kamu menggunakan kecerdasan buatan berbasis Fuzzy Logic Mamdani.</p>
        <div class="hero-cta">
            <a href="{{ route('diagnosis.create') }}" class="btn-primary">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 8v4l3 3" />
                </svg>
                Cek Risiko Sekarang
            </a>
            <a href="#cara-kerja" class="btn-outline">Pelajari cara kerja</a>
        </div>
        <p class="hero-note">✓ Gratis &nbsp;&nbsp; ✓ Tanpa daftar &nbsp;&nbsp; ✓ Tidak perlu alat medis</p>
    </div>

    {{-- STATS --}}
    <div class="stats-bar">
        <div class="stat-card">
            <div class="stat-num">6</div>
            <div class="stat-label">Variabel input yang bisa diisi tanpa alat medis apapun</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">30</div>
            <div class="stat-label">Basis aturan fuzzy IF-THEN metode Mamdani</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">4</div>
            <div class="stat-label">Tingkat risiko — Rendah, Waspada, Tinggi, Sangat Tinggi</div>
        </div>
    </div>

    {{-- FITUR --}}
    <section>
        <div class="section-tag">✦ Keunggulan</div>
        <h2>Kenapa pakai <em>DiagnoCare?</em></h2>
        <p class="section-desc">Dirancang khusus untuk masyarakat umum — tidak perlu latar belakang medis atau alat kesehatan khusus.</p>

        <div class="fitur-grid">
            <div class="fitur-card">
                <div class="fitur-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                    </svg>
                </div>
                <div class="fitur-title">Untuk Semua Orang</div>
                <div class="fitur-desc">Tidak perlu alat cek gula darah atau tensimeter. Cukup isi pertanyaan berdasarkan kondisi dan kebiasaan sehari-hari.</div>
            </div>
            <div class="fitur-card">
                <div class="fitur-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                    </svg>
                </div>
                <div class="fitur-title">Hasil Instan</div>
                <div class="fitur-desc">Sistem langsung memproses data menggunakan algoritma Fuzzy Mamdani dan menampilkan skor risiko beserta rekomendasi tindakan.</div>
            </div>
            <div class="fitur-card">
                <div class="fitur-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                </div>
                <div class="fitur-title">Transparan & Terjelaskan</div>
                <div class="fitur-desc">Setiap hasil dilengkapi detail derajat keanggotaan, rules yang aktif, dan agregasi output sehingga kamu tahu bagaimana sistem mengambil keputusan.</div>
            </div>
        </div>
    </section>

    {{-- INPUT VARIABEL --}}
    <section>
        <div class="section-tag">✦ Variabel Input</div>
        <h2>Apa saja yang <em>perlu diisi?</em></h2>
        <p class="section-desc">Enam variabel input yang semuanya bisa kamu ketahui sendiri tanpa perlu ke laboratorium.</p>

        <div class="input-grid">
            <div class="input-card">
                <div class="input-icon"><svg viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg></div>
                <div>
                    <div class="input-title">Usia</div>
                    <div class="input-desc">Usia dalam tahun. Risiko diabetes meningkat seiring bertambahnya usia.</div>
                </div>
            </div>
            <div class="input-card">
                <div class="input-icon"><svg viewBox="0 0 24 24">
                        <path d="M3 6l9-4 9 4v6c0 5.25-3.75 10.15-9 11.5C6.75 22.15 3 17.25 3 12V6z" />
                    </svg></div>
                <div>
                    <div class="input-title">Berat & Tinggi Badan</div>
                    <div class="input-desc">BMI dihitung otomatis. Obesitas adalah faktor risiko utama diabetes tipe 2.</div>
                </div>
            </div>
            <div class="input-card">
                <div class="input-icon"><svg viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        <path d="M9 12l2 2 4-4" />
                    </svg></div>
                <div>
                    <div class="input-title">Gejala 3P</div>
                    <div class="input-desc">Seberapa sering kamu merasa sangat haus, lapar, atau sering buang air kecil di malam hari.</div>
                </div>
            </div>
            <div class="input-card">
                <div class="input-icon"><svg viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
                    </svg></div>
                <div>
                    <div class="input-title">Luka / Kesemutan</div>
                    <div class="input-desc">Apakah kamu sering mengalami luka yang lama sembuh atau kesemutan di tangan dan kaki.</div>
                </div>
            </div>
            <div class="input-card">
                <div class="input-icon"><svg viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                    </svg></div>
                <div>
                    <div class="input-title">Riwayat Keluarga</div>
                    <div class="input-desc">Ada tidaknya anggota keluarga yang menderita diabetes dan seberapa dekat hubungannya.</div>
                </div>
            </div>
            <div class="input-card">
                <div class="input-icon"><svg viewBox="0 0 24 24">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                    </svg></div>
                <div>
                    <div class="input-title">Aktivitas Fisik</div>
                    <div class="input-desc">Berapa hari dalam seminggu kamu berolahraga minimal 30 menit. Gaya hidup aktif menurunkan risiko diabetes.</div>
                </div>
            </div>
        </div>
    </section>

    {{-- CARA KERJA --}}
    <section id="cara-kerja">
        <div class="section-tag">✦ Cara Kerja</div>
        <h2>Proses <em>Fuzzy Mamdani</em></h2>
        <p class="section-desc">DiagnoCare memproses inputmu melalui 4 tahap algoritma Fuzzy Logic Mamdani untuk menghasilkan skor risiko yang akurat.</p>

        <div class="steps">
            <div class="step-card">
                <div class="step-num">01</div>
                <div class="step-tag">Fuzzifikasi</div>
                <div class="step-title">Nilai Crisp → Derajat Keanggotaan</div>
                <div class="step-desc">Setiap nilai input diubah menjadi derajat keanggotaan pada himpunan fuzzy seperti Muda, Parobaya, Lansia menggunakan fungsi trapesium dan segitiga.</div>
            </div>
            <div class="step-card">
                <div class="step-num">02</div>
                <div class="step-tag">Inferensi</div>
                <div class="step-title">Evaluasi 30 Rules IF-THEN</div>
                <div class="step-desc">Setiap aturan dievaluasi menggunakan operator MIN. Contoh: IF Usia Lansia AND BMI Obesitas AND Gejala Berat THEN Risiko Sangat Tinggi.</div>
            </div>
            <div class="step-card">
                <div class="step-num">03</div>
                <div class="step-tag">Agregasi</div>
                <div class="step-title">Gabungkan Semua Output</div>
                <div class="step-desc">Output dari semua rules yang aktif digabungkan menggunakan operator MAX untuk menghasilkan satu himpunan fuzzy output.</div>
            </div>
            <div class="step-card">
                <div class="step-num">04</div>
                <div class="step-tag">Defuzzifikasi</div>
                <div class="step-title">Hitung Skor Akhir</div>
                <div class="step-desc">Himpunan fuzzy output diubah menjadi satu nilai numerik menggunakan metode Centroid — titik pusat massa dari kurva output.</div>
            </div>
        </div>
    </section>

    {{-- CTA BOTTOM --}}
    <div class="cta-bottom">
        <h2 style="color:white;letter-spacing:-0.5px">Siap cek risiko diabetes kamu?</h2>
        <p>Gratis, cepat, dan tidak perlu alat medis apapun. Hanya butuh 2 menit.</p>
        <a href="{{ route('diagnosis.create') }}" class="btn-white">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <path d="M12 8v4l3 3" />
            </svg>
            Mulai Diagnosa Sekarang
        </a>
    </div>

    {{-- FOOTER --}}
    <footer>
        DiagnoCare &mdash; Sistem Diagnosa Risiko Diabetes &mdash; Fuzzy Logic Mamdani &mdash; D4 Teknik Informatika UNAIR 2026
    </footer>

</body>

</html>