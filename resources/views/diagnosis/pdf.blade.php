<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Skrining Risiko Diabetes</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333333;
            line-height: 1.4;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #1e3a8a;
            font-size: 18pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 10pt;
            color: #555555;
        }
        .section-title {
            font-size: 13pt;
            color: #1e3a8a;
            border-left: 4px solid #1e3a8a;
            padding-left: 8px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #f3f4f6;
            color: #111827;
            text-align: left;
            font-weight: bold;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 11pt;
        }
        .badge-risiko {
            background-color: {{ $warnaHex }};
            color: #ffffff;
            padding: 8px 15px;
            font-weight: bold;
            font-size: 14pt;
            text-align: center;
            border-radius: 4px;
            margin-top: 5px;
            display: inline-block;
        }
        .box-rekomendasi {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 5px solid #64748b;
            padding: 12px;
            border-radius: 4px;
            font-size: 11pt;
            text-align: justify;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9pt;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>DIAGNOCARE SYSTEM</h2>
        <p>Sistem Pakar Skrining Dini Risiko Diabetes Melitus Mandiri</p>
        <p>Tanggal Pemeriksaan: {{ $diagnosis->created_at->format('d F Y H:i') }} WIB</p>
    </div>

    <div class="section-title">Profil Fisik & Gejala Pasien</div>
    <table class="info-table">
        <tr>
            <td style="width: 25%;"><strong>Nama Pasien</strong></td>
            <td style="width: 2%;">:</td>
            <td>{{ $diagnosis->nama_pasien }}</td>
            <td style="width: 25%;"><strong>Gejala 3P</strong></td>
            <td style="width: 2%;">:</td>
            <td>{{ ucfirst($diagnosis->gejala_3p) }}</td>
        </tr>
        <tr>
            <td><strong>Usia</strong></td>
            <td>:</td>
            <td>{{ $diagnosis->usia }} Tahun</td>
            <td><strong>Gejala Luka</strong></td>
            <td>:</td>
            <td>{{ ucfirst($diagnosis->gejala_luka) }}</td>
        </tr>
        <tr>
            <td><strong>Berat Badan</strong></td>
            <td>:</td>
            <td>{{ $diagnosis->berat_badan }} kg</td>
            <td><strong>Riwayat Keluarga</strong></td>
            <td>:</td>
            <td>Skala {{ $diagnosis->riwayat_keluarga }}/10</td>
        </tr>
        <tr>
            <td><strong>Tinggi Badan</strong></td>
            <td>:</td>
            <td>{{ $diagnosis->tinggi_badan }} cm</td>
            <td><strong>Aktivitas Fisik</strong></td>
            <td>:</td>
            <td>{{ $diagnosis->aktivitas_fisik }} Hari/Minggu</td>
        </tr>
        <tr>
            <td><strong>Kategori BMI</strong></td>
            <td>:</td>
            <td colspan="4"><strong>{{ $diagnosis->bmi }}</strong> (Indeks Massa Tubuh)</td>
        </tr>
    </table>

    <div class="section-title">Hasil Analisis Inferensi Fuzzy Mamdani</div>
    <table style="width: 100%; margin-top: 10px;">
        <tr>
            <td style="width: 50%; text-align: center; padding: 15px; border: 1px solid #e5e7eb; background-color: #fafafa;">
                <div style="font-size: 11pt; color: #4b5563;">Skor Probabilitas Risiko</div>
                <div style="font-size: 28pt; font-weight: bold; color: {{ $warnaHex }}; margin: 5px 0;">
                    {{ $diagnosis->skor_risiko }}%
                </div>
            </td>
            <td style="width: 50%; text-align: center; padding: 15px; border: 1px solid #e5e7eb; background-color: #fafafa; vertical-align: middle;">
                <div style="font-size: 11pt; color: #4b5563; margin-bottom: 5px;">Klasifikasi Tingkat Risiko</div>
                <div class="badge-risiko">{{ $diagnosis->klasifikasi }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Rekomendasi Tindakan Tindak Lanjut</div>
    <div class="box-rekomendasi">
        {{ $diagnosis->rekomendasi }}
    </div>

    @if(!empty($detailFuzzy['rules_fired']))
    <div class="section-title" style="page-break-before: always;">Lampiran Teknis: Aturan Fuzzy yang Aktif</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%; text-align: center;">No.</th>
                <th style="width: 65%;">Basis Aturan Medis (Fuzzy Rules)</th>
                <th style="width: 25%; text-align: center;">Bobot Kontribusi (&alpha;)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detailFuzzy['rules_fired'] as $index => $rule)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>Hasil inferensi berkontribusi pada indikasi risiko <strong>{{ ucfirst(str_replace('_', ' ', $rule['output'])) }}</strong></td>
                <td style="text-align: center; font-family: monospace;">{{ $rule['bobot'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="box-rekomendasi" style="margin-top: 25px; border-left-color: #ef4444; background-color: #fff1f2; font-size: 10pt;">
        <strong>Catatan Penting (Disclaimer):</strong> Hasil skrining ini dihasilkan menggunakan komputasi logika matematika kecerdasan buatan berbasis pedoman umum faktor diabetes. Hasil ini ditujukan sebagai bentuk <strong>kewaspadaan dini dan skrining awal</strong>, bukan merupakan hasil diagnosis klinis mutlak dari dokter spesialis. Anda sangat disarankan untuk melakukan validasi laboratorium (Tes Gula Darah Puasa/HbA1c) di Puskesmas atau Rumah Sakit terdekat.
    </div>

    <div class="footer">
        Dokumen dicetak otomatis oleh Aplikasi Diagnocare System &copy; {{ date('Y') }} - Privasi Data Pasien Dilindungi.
    </div>

</body>
</html>