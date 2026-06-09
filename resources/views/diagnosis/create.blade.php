@extends('layouts.app')
@section('title', 'Diagnosa Baru')

@section('content')
<div class="hero">
    <div class="hero-tag"><span></span> Sistem Pakar Kecerdasan Buatan</div>
    <h1>Cek risiko <em>diabetes</em> kamu</h1>
    <p class="hero-desc">Isi pertanyaan berikut berdasarkan kondisi dan kebiasaan sehari-hari. Tidak perlu alat medis khusus.</p>
</div>

<div class="container">
    <div class="form-card">
        <form action="{{ route('diagnosis.store') }}" method="POST">
            @csrf

            {{-- ── SEKSI A: Data Diri ── --}}
            <div class="form-section">
                <div class="form-section-title">
                    Data Diri <span>A</span>
                </div>

                <div class="field">
                    <label class="field-label" for="nama_pasien">
                        Nama <span class="optional">(opsional)</span>
                    </label>
                    <div class="input-outer">
                        <input type="text" id="nama_pasien" name="nama_pasien"
                            class="input-text @error('nama_pasien') error @enderror"
                            value="{{ old('nama_pasien') }}"
                            placeholder="mis. Budi Santoso">
                    </div>
                    @error('nama_pasien')
                    <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-grid">
                    {{-- Usia --}}
                    <div class="field">
                        <div class="field-top">
                            <label class="field-label" for="usia">Usia</label>
                            <span class="field-range">1 – 100</span>
                        </div>
                        <div class="input-outer">
                            <input type="number" id="usia" name="usia" step="1"
                                class="input-num @error('usia') error @enderror"
                                value="{{ old('usia') }}"
                                placeholder="mis. 35" min="1" max="100">
                            <span class="input-unit">tahun</span>
                        </div>
                        @error('usia')
                        <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Placeholder kolom kanan --}}
                    <div></div>
                </div>

                {{-- Berat & Tinggi --}}
                <div class="field-grid" style="margin-top:1rem">
                    <div class="field">
                        <div class="field-top">
                            <label class="field-label" for="berat_badan">Berat badan</label>
                            <span class="field-range">20 – 200</span>
                        </div>
                        <div class="input-outer">
                            <input type="number" id="berat_badan" name="berat_badan" step="0.1"
                                class="input-num @error('berat_badan') error @enderror"
                                value="{{ old('berat_badan') }}"
                                placeholder="mis. 65" min="20" max="200">
                            <span class="input-unit">kg</span>
                        </div>
                        @error('berat_badan')
                        <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <div class="field-top">
                            <label class="field-label" for="tinggi_badan">Tinggi badan</label>
                            <span class="field-range">100 – 250</span>
                        </div>
                        <div class="input-outer">
                            <input type="number" id="tinggi_badan" name="tinggi_badan" step="0.1"
                                class="input-num @error('tinggi_badan') error @enderror"
                                value="{{ old('tinggi_badan') }}"
                                placeholder="mis. 165" min="100" max="250">
                            <span class="input-unit">cm</span>
                        </div>
                        @error('tinggi_badan')
                        <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- BMI Preview --}}
                <div class="bmi-preview" id="bmi-preview">
                    <div>
                        <div style="font-size:11px;font-family:var(--mono);color:var(--text3);margin-bottom:2px">BMI DIHITUNG OTOMATIS</div>
                        <span class="bmi-val" id="bmi-val">–</span>
                        <span class="bmi-label" id="bmi-label" style="margin-left:8px"></span>
                    </div>
                    <span class="field-hint" id="bmi-hint"></span>
                </div>
            </div>

            {{-- ── SEKSI B: Gejala ── --}}
            <div class="form-section">
                <div class="form-section-title">
                    Gejala yang Dirasakan <span>B</span>
                </div>

                {{-- Gejala 3P --}}
                <div class="field">
                    <div class="field-label" style="margin-bottom:6px">
                        Gejala 3P (Sering haus, sering lapar, sering buang air kecil di malam hari)
                    </div>
                    <div class="field-desc">Seberapa sering kamu mengalami gejala-gejala tersebut belakangan ini?</div>
                    <div class="radio-group">
                        @foreach([
                        ['tidak', '😊', 'Tidak Ada', 'Jarang sekali'],
                        ['kadang', '😐', 'Kadang-kadang', '1-2x seminggu'],
                        ['sering', '😟', 'Sering', 'Hampir setiap hari'],
                        ] as [$val, $icon, $label, $sub])
                        <div class="radio-option">
                            <input type="radio" id="gejala_3p_{{ $val }}" name="gejala_3p"
                                value="{{ $val }}" {{ old('gejala_3p') === $val ? 'checked' : '' }}>
                            <label for="gejala_3p_{{ $val }}">
                                <span class="radio-icon">{{ $icon }}</span>
                                <span class="radio-text">{{ $label }}</span>
                                <span class="radio-sub">{{ $sub }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('gejala_3p')
                    <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Gejala Luka --}}
                <div class="field" style="margin-top:1.25rem">
                    <div class="field-label" style="margin-bottom:6px">
                        Luka Sulit Sembuh / Kesemutan (Neuropati)
                    </div>
                    <div class="field-desc">Apakah kamu sering mengalami luka yang lama sembuh atau kesemutan di tangan/kaki?</div>
                    <div class="radio-group">
                        @foreach([
                        ['tidak', '✅', 'Tidak Ada', 'Luka sembuh normal'],
                        ['kadang', '⚠️', 'Kadang-kadang', 'Sesekali terjadi'],
                        ['sering', '🚨', 'Sering', 'Sering terjadi'],
                        ] as [$val, $icon, $label, $sub])
                        <div class="radio-option">
                            <input type="radio" id="gejala_luka_{{ $val }}" name="gejala_luka"
                                value="{{ $val }}" {{ old('gejala_luka') === $val ? 'checked' : '' }}>
                            <label for="gejala_luka_{{ $val }}">
                                <span class="radio-icon">{{ $icon }}</span>
                                <span class="radio-text">{{ $label }}</span>
                                <span class="radio-sub">{{ $sub }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('gejala_luka')
                    <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ── SEKSI C: Riwayat & Gaya Hidup ── --}}
            <div class="form-section">
                <div class="form-section-title">
                    Riwayat &amp; Gaya Hidup <span>C</span>
                </div>

                {{-- Riwayat Keluarga --}}
                <div class="field">
                    <div class="field-label" style="margin-bottom:6px">Riwayat diabetes dalam keluarga</div>
                    <div class="field-desc">Seberapa dekat hubungan anggota keluarga yang menderita diabetes?</div>
                    <div class="radio-group">
                        @foreach([
                        ['0', '✅', 'Tidak Ada', 'Tidak ada riwayat'],
                        ['5', '⚠️', 'Keluarga Jauh', 'Kakek/nenek/paman'],
                        ['10', '🚨', 'Keluarga Kandung', 'Orang tua/saudara'],
                        ] as [$val, $icon, $label, $sub])
                        <div class="radio-option">
                            <input type="radio" id="riwayat_{{ $val }}" name="riwayat_keluarga"
                                value="{{ $val }}" {{ old('riwayat_keluarga') === $val ? 'checked' : '' }}>
                            <label for="riwayat_{{ $val }}">
                                <span class="radio-icon">{{ $icon }}</span>
                                <span class="radio-text">{{ $label }}</span>
                                <span class="radio-sub">{{ $sub }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('riwayat_keluarga')
                    <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Aktivitas Fisik --}}
                <div class="field" style="margin-top:1.25rem">
                    <div class="field-top">
                        <div class="field-label">Aktivitas fisik / olahraga</div>
                    </div>
                    <div class="field-desc">Berapa hari dalam seminggu kamu berolahraga minimal 30 menit?</div>
                    <div class="range-wrap">
                        <input type="range" id="aktivitas_fisik" name="aktivitas_fisik"
                            min="0" max="7" step="1"
                            value="{{ old('aktivitas_fisik', 3) }}"
                            oninput="document.getElementById('akt-val').textContent = this.value">
                        <span class="range-val" id="akt-val">{{ old('aktivitas_fisik', 3) }}</span>
                        <span style="font-size:12px;color:var(--text3)">hari/minggu</span>
                    </div>
                    <div class="range-labels">
                        <span>0 — Tidak pernah</span>
                        <span>3-4 — Sedang</span>
                        <span>7 — Setiap hari</span>
                    </div>
                    @error('aktivitas_fisik')
                    <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-run">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 8v4l3 3" />
                </svg>
                Cek Risiko Diabetes Saya
            </button>

        </form>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-num">6</div>
            <div class="stat-label">Variabel input — tanpa alat medis, cukup isi sendiri</div>
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
</div>
@endsection

@push('scripts')
<script>
    function hitungBMI() {
        const bb = parseFloat(document.getElementById('berat_badan').value);
        const tb = parseFloat(document.getElementById('tinggi_badan').value);
        const preview = document.getElementById('bmi-preview');
        const bmiVal = document.getElementById('bmi-val');
        const bmiHint = document.getElementById('bmi-hint');

        if (bb > 0 && tb > 0) {
            const bmi = bb / ((tb / 100) * (tb / 100));
            const bmiRound = Math.round(bmi * 10) / 10;
            bmiVal.textContent = bmiRound;
            preview.classList.add('show');

            if (bmi < 18.5) {
                bmiHint.textContent = 'Kurus';
                bmiHint.className = 'field-hint hint-warn';
            } else if (bmi < 25) {
                bmiHint.textContent = 'Normal';
                bmiHint.className = 'field-hint hint-ok';
            } else if (bmi < 30) {
                bmiHint.textContent = 'Overweight';
                bmiHint.className = 'field-hint hint-warn';
            } else {
                bmiHint.textContent = 'Obesitas';
                bmiHint.className = 'field-hint hint-danger';
            }
        } else {
            preview.classList.remove('show');
        }
    }

    document.getElementById('berat_badan').addEventListener('input', hitungBMI);
    document.getElementById('tinggi_badan').addEventListener('input', hitungBMI);
</script>
@endpush