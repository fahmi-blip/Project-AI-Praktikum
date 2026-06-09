@extends('layouts.app')
@section('title', 'Diagnosa Baru')

@section('content')
<div class="hero">
    <div class="hero-tag"><span></span> Sistem Pakar Kecerdasan Buatan</div>
    <h1>Diagnosa <em>risiko</em> diabetes lebih awal</h1>
    <p class="hero-desc">Masukkan data kesehatan Anda. Sistem akan menganalisis menggunakan logika fuzzy metode Mamdani.</p>
</div>

<div class="container">
    <div class="form-card">
        <div class="card-title">Data pasien</div>

        <form action="{{ route('diagnosis.store') }}" method="POST">
            @csrf

            {{-- Nama Pasien --}}
            <div class="field">
                <label class="field-label" for="nama_pasien">
                    Nama pasien <span class="optional">(opsional)</span>
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

                {{-- Gula Darah --}}
                <div class="field">
                    <div class="field-top">
                        <label class="field-label" for="gula_darah">Kadar gula darah puasa</label>
                        <span class="field-range">70 – 200</span>
                    </div>
                    <div class="input-outer">
                        <input type="number" id="gula_darah" name="gula_darah" step="1"
                            class="input-num @error('gula_darah') error @enderror"
                            value="{{ old('gula_darah') }}"
                            placeholder="mis. 90" min="70" max="200">
                        <span class="input-unit">mg/dL</span>
                    </div>
                    @error('gula_darah')
                    <div class="field-error">{{ $message }}</div>
                    @enderror
                    <div class="field-hint" id="hint-gula">Masukkan nilai antara 70 – 200 mg/dL</div>
                </div>

                {{-- Tekanan Darah --}}
                <div class="field">
                    <div class="field-top">
                        <label class="field-label" for="tekanan_darah">Tekanan darah sistolik</label>
                        <span class="field-range">80 – 180</span>
                    </div>
                    <div class="input-outer">
                        <input type="number" id="tekanan_darah" name="tekanan_darah" step="1"
                            class="input-num @error('tekanan_darah') error @enderror"
                            value="{{ old('tekanan_darah') }}"
                            placeholder="mis. 115" min="80" max="180">
                        <span class="input-unit">mmHg</span>
                    </div>
                    @error('tekanan_darah')
                    <div class="field-error">{{ $message }}</div>
                    @enderror
                    <div class="field-hint" id="hint-tensi">Masukkan nilai antara 80 – 180 mmHg</div>
                </div>

                {{-- BMI --}}
                <div class="field">
                    <div class="field-top">
                        <label class="field-label" for="bmi">Indeks massa tubuh (BMI)</label>
                        <span class="field-range">15.0 – 45.0</span>
                    </div>
                    <div class="input-outer">
                        <input type="number" id="bmi" name="bmi" step="0.1"
                            class="input-num @error('bmi') error @enderror"
                            value="{{ old('bmi') }}"
                            placeholder="mis. 22.5" min="15" max="45">
                        <span class="input-unit">kg/m²</span>
                    </div>
                    @error('bmi')
                    <div class="field-error">{{ $message }}</div>
                    @enderror
                    <div class="field-hint" id="hint-bmi">Masukkan nilai antara 15.0 – 45.0 kg/m²</div>
                </div>

                {{-- Usia --}}
                <div class="field">
                    <div class="field-top">
                        <label class="field-label" for="usia">Usia</label>
                        <span class="field-range">10 – 80</span>
                    </div>
                    <div class="input-outer">
                        <input type="number" id="usia" name="usia" step="1"
                            class="input-num @error('usia') error @enderror"
                            value="{{ old('usia') }}"
                            placeholder="mis. 30" min="10" max="80">
                        <span class="input-unit">tahun</span>
                    </div>
                    @error('usia')
                    <div class="field-error">{{ $message }}</div>
                    @enderror
                    <div class="field-hint" id="hint-usia">Masukkan nilai antara 10 – 80 tahun</div>
                </div>

            </div>

            <button type="submit" class="btn-run">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 8v4l3 3" />
                </svg>
                Mulai diagnosa
            </button>
        </form>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-num">4</div>
            <div class="stat-label">Variabel input — gula darah, tekanan darah, BMI, dan usia</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">22</div>
            <div class="stat-label">Basis aturan fuzzy IF-THEN dengan metode inferensi MIN-MAX</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">3</div>
            <div class="stat-label">Kelas output — risiko rendah, sedang, dan tinggi</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const hints = {
        gula: v => v < 100 ? 'Normal (<100 mg/dL)' : v < 126 ? 'Pra-diabetes (100–125 mg/dL)' : 'Diabetes (≥126 mg/dL)',
        tensi: v => v < 120 ? 'Normal (<120 mmHg)' : v < 140 ? 'Pra-hipertensi (120–139 mmHg)' : 'Hipertensi (≥140 mmHg)',
        bmi: v => v < 18.5 ? 'Kurus (<18.5)' : v < 25 ? 'Normal (18.5–24.9)' : v < 30 ? 'Kelebihan berat (25–29.9)' : 'Obesitas (≥30)',
        usia: v => v < 35 ? 'Muda (<35 tahun)' : v < 56 ? 'Dewasa (35–55 tahun)' : 'Lansia (≥56 tahun)',
    };
    const cls = {
        gula: v => v < 100 ? 'hint-ok' : v < 126 ? 'hint-warn' : 'hint-danger',
        tensi: v => v < 120 ? 'hint-ok' : v < 140 ? 'hint-warn' : 'hint-danger',
        bmi: v => v < 18.5 ? 'hint-warn' : v < 25 ? 'hint-ok' : v < 30 ? 'hint-warn' : 'hint-danger',
        usia: v => v < 35 ? 'hint-ok' : v < 56 ? 'hint-warn' : 'hint-danger',
    };

    [
        ['gula_darah', 'gula'],
        ['tekanan_darah', 'tensi'],
        ['bmi', 'bmi'],
        ['usia', 'usia'],
    ].forEach(([id, key]) => {
        const el = document.getElementById(id);
        const hint = document.getElementById('hint-' + key);
        if (!el || !hint) return;
        el.addEventListener('input', () => {
            const v = parseFloat(el.value);
            if (!isNaN(v)) {
                hint.textContent = hints[key](v);
                hint.className = 'field-hint ' + cls[key](v);
            }
        });
    });
</script>
@endpush