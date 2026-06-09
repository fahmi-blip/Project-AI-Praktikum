@extends('layouts.app')
@section('title', 'Hasil Diagnosa')

@section('content')
<div class="container" style="max-width:820px">

    {{-- Header --}}
    <div class="result-header-block">
        <div>
            <div class="result-patient">{{ $diagnosis->nama_pasien }}</div>
            <div class="result-date">{{ $diagnosis->created_at->format('d F Y, H:i') }} WIB</div>
        </div>
        <a href="{{ route('diagnosis.create') }}" class="btn-secondary">+ Cek ulang</a>
    </div>

    {{-- Skor utama --}}
    <div class="score-card">
        <div class="score-main">
            <div class="score-num c-{{ $diagnosis->warna }}">
                {{ number_format($diagnosis->skor_risiko, 1) }}<span class="score-denom">%</span>
            </div>
            <div>
                <span class="risk-badge badge-{{ $diagnosis->warna }}">
                    <span class="badge-dot"></span>
                    Risiko {{ $diagnosis->klasifikasi }}
                </span>
                <div style="font-size:12px;color:var(--text3);margin-top:6px;font-family:var(--mono)">
                    Skor probabilitas diabetes
                </div>
            </div>
        </div>
        <div class="gauge-track">
            <div class="gauge-fill bg-{{ $diagnosis->warna }}"
                style="width:{{ $diagnosis->skor_risiko }}%"></div>
        </div>
        <div class="gauge-labels">
            <span>0% — Rendah</span>
            <span>30% — Waspada</span>
            <span>60% — Tinggi</span>
            <span>85% — Sangat Tinggi</span>
        </div>
    </div>

    <div class="result-grid">

        {{-- Data input --}}
        <div class="card">
            <div class="card-title">Data yang diinput</div>
            <div class="input-summary">
                <div class="input-row">
                    <span class="input-row-label">Usia</span>
                    <span class="input-row-val">{{ $diagnosis->usia }} <span class="input-row-unit">tahun</span></span>
                </div>
                <div class="input-row">
                    <span class="input-row-label">Berat badan</span>
                    <span class="input-row-val">{{ $diagnosis->berat_badan }} <span class="input-row-unit">kg</span></span>
                </div>
                <div class="input-row">
                    <span class="input-row-label">Tinggi badan</span>
                    <span class="input-row-val">{{ $diagnosis->tinggi_badan }} <span class="input-row-unit">cm</span></span>
                </div>
                <div class="input-row">
                    <span class="input-row-label">BMI (otomatis)</span>
                    <span class="input-row-val">{{ $diagnosis->bmi }} <span class="input-row-unit">kg/m²</span></span>
                </div>
                <div class="input-row">
                    <span class="input-row-label">Gejala 3P</span>
                    <span class="input-row-val">{{ $diagnosis->label_3p }}</span>
                </div>
                <div class="input-row">
                    <span class="input-row-label">Luka/Kesemutan</span>
                    <span class="input-row-val">{{ $diagnosis->label_luka }}</span>
                </div>
                <div class="input-row">
                    <span class="input-row-label">Riwayat keluarga</span>
                    <span class="input-row-val">
                        @if($diagnosis->riwayat_keluarga == 0) Tidak Ada
                        @elseif($diagnosis->riwayat_keluarga == 5) Keluarga Jauh
                        @else Keluarga Kandung
                        @endif
                    </span>
                </div>
                <div class="input-row">
                    <span class="input-row-label">Aktivitas fisik</span>
                    <span class="input-row-val">{{ $diagnosis->aktivitas_fisik }} <span class="input-row-unit">hari/minggu</span></span>
                </div>
            </div>
        </div>

        {{-- Derajat keanggotaan --}}
        <div class="card">
            <div class="card-title">Derajat keanggotaan</div>
            @php $derajat = $diagnosis->detail_fuzzy['derajat'] ?? []; @endphp

            @foreach([
            ['Usia', 'usia', ['Muda','Parobaya','Lansia']],
            ['BMI', 'bmi', ['Kurus','Normal','Overweight','Obesitas']],
            ['Gejala 3P', 'gejala3p', ['Ringan','Sedang','Berat']],
            ['Luka/Kesem', 'gejaleLuka', ['Tidak Ada','Kadang','Sering']],
            ['Riwayat', 'riwayat', ['Aman','Rentan','Risiko Tinggi']],
            ['Aktivitas', 'aktivitas', ['Pasif','Sedang','Aktif']],
            ] as [$label, $key, $labels])
            <div class="mf-var">
                <div class="mf-var-name">{{ $label }}</div>
                @php $vals = array_values($derajat[$key] ?? []); @endphp
                @foreach($labels as $i => $lbl)
                @php
                $pct = isset($vals[$i]) ? round($vals[$i] * 100) : 0;
                $color = in_array($lbl, ['Muda','Normal','Aman','Aktif','Tidak Ada'])
                ? '#2B5D4F'
                : (in_array($lbl, ['Lansia','Obesitas','Risiko Tinggi','Berat','Sering','Pasif'])
                ? '#8B2020' : '#8B5A1A');
                @endphp
                <div class="mf-row">
                    <span class="mf-name">{{ $lbl }}</span>
                    <div class="mf-track">
                        <div class="mf-bar" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                    </div>
                    <span class="mf-pct">{{ $pct }}%</span>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

        {{-- Agregasi output --}}
        <div class="card">
            <div class="card-title">Agregasi output (MAX)</div>
            @php $agg = $diagnosis->detail_fuzzy['agregasi'] ?? []; @endphp

            @foreach([
            'rendah' => ['#2B5D4F', 'Rendah'],
            'waspada' => ['#8B5A1A', 'Waspada'],
            'tinggi' => ['#8B4010', 'Tinggi'],
            'sangat_tinggi' => ['#8B2020', 'Sangat Tinggi'],
            ] as $key => [$color, $label])
            @php $pct = isset($agg[$key]) ? round($agg[$key] * 100) : 0; @endphp
            <div class="agg-row">
                <span class="agg-label">{{ $label }}</span>
                <div class="agg-track">
                    <div class="agg-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                </div>
                <span class="agg-val">{{ $pct }}%</span>
            </div>
            @endforeach
        </div>

        {{-- Rules fired --}}
        <div class="card">
            @php $fired = $diagnosis->detail_fuzzy['rules_fired'] ?? []; @endphp
            <div class="card-title">
                Rules aktif
                <span style="font-weight:400;margin-left:4px">({{ count($fired) }} dari 30)</span>
            </div>
            <div class="rules-list">
                @foreach($fired as $rule)
                @php
                $rc = match($rule['output']) {
                'rendah' => '#2B5D4F',
                'waspada' => '#8B5A1A',
                'tinggi' => '#8B4010',
                default => '#8B2020',
                };
                @endphp
                <div class="rule-item">
                    <span class="rule-out" style="color:{{ $rc }}">
                        → {{ ucfirst(str_replace('_', ' ', $rule['output'])) }}
                    </span>
                    <span class="rule-w">{{ number_format($rule['bobot'], 3) }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Rekomendasi --}}
    <div class="rek-card rek-{{ $diagnosis->warna }}">
        <div class="rek-icon rek-icon-{{ $diagnosis->warna }}">
            @if($diagnosis->warna === 'green')
            <svg viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @elseif($diagnosis->warna === 'amber')
            <svg viewBox="0 0 24 24">
                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            @elseif($diagnosis->warna === 'orange')
            <svg viewBox="0 0 24 24">
                <path d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>
            @else
            <svg viewBox="0 0 24 24">
                <path d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>
            @endif
        </div>
        <div>
            <div class="rek-title">Rekomendasi</div>
            <div class="rek-text">{{ $diagnosis->rekomendasi }}</div>
        </div>
    </div>

    <div style="text-align:center;margin-top:1.5rem">
        <a href="{{ route('diagnosis.index') }}" class="btn-secondary">
            Lihat semua riwayat →
        </a>
    </div>

</div>
@endsection