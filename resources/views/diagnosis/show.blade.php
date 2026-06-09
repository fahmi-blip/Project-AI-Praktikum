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
        <a href="{{ route('diagnosis.create') }}" class="btn-secondary">+ Diagnosa baru</a>
    </div>

    {{-- Skor utama --}}
    <div class="score-card">
        <div class="score-main">
            <div class="score-num c-{{ $diagnosis->warna }}">
                {{ number_format($diagnosis->skor_risiko, 1) }}<span class="score-denom">/100</span>
            </div>
            <span class="risk-badge badge-{{ $diagnosis->warna }}">
                <span class="badge-dot"></span>
                Risiko {{ $diagnosis->klasifikasi }}
            </span>
        </div>
        <div class="gauge-track">
            <div class="gauge-fill bg-{{ $diagnosis->warna }}"
                style="width: {{ $diagnosis->skor_risiko }}%"></div>
        </div>
        <div class="gauge-labels">
            <span>0 — Rendah</span>
            <span>50 — Sedang</span>
            <span>100 — Tinggi</span>
        </div>
    </div>

    <div class="result-grid">

        {{-- Data input pasien --}}
        <div class="card">
            <div class="card-title">Data input pasien</div>
            <div class="input-summary">
                @foreach([
                ['Gula Darah', $diagnosis->gula_darah, 'mg/dL'],
                ['Tekanan Darah', $diagnosis->tekanan_darah, 'mmHg'],
                ['BMI', $diagnosis->bmi, 'kg/m²'],
                ['Usia', $diagnosis->usia, 'tahun'],
                ] as [$label, $val, $unit])
                <div class="input-row">
                    <span class="input-row-label">{{ $label }}</span>
                    <span class="input-row-val">
                        {{ $val }} <span class="input-row-unit">{{ $unit }}</span>
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Derajat keanggotaan --}}
        <div class="card">
            <div class="card-title">Derajat keanggotaan input</div>
            @php $derajat = $diagnosis->detail_fuzzy['derajat'] ?? []; @endphp

            @foreach([
            ['Gula Darah', 'gula', ['Normal', 'Pra-diabetes', 'Diabetes']],
            ['Tensi', 'tensi', ['Normal', 'Sedang', 'Tinggi']],
            ['BMI', 'bmi', ['Kurus', 'Normal', 'Lebih', 'Obese']],
            ['Usia', 'usia', ['Muda', 'Dewasa', 'Lansia']],
            ] as [$label, $key, $labels])
            <div class="mf-var">
                <div class="mf-var-name">{{ $label }}</div>
                @php $vals = array_values($derajat[$key] ?? []); @endphp
                @foreach($labels as $i => $lbl)
                @php
                $pct = isset($vals[$i]) ? round($vals[$i] * 100) : 0;
                $color = in_array($lbl, ['Normal','Kurus','Muda'])
                ? '#2B5D4F'
                : (in_array($lbl, ['Diabetes','Obese','Lansia','Tinggi'])
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
            'sedang' => ['#8B5A1A', 'Sedang'],
            'tinggi' => ['#8B2020', 'Tinggi'],
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
                <span style="font-weight:400;margin-left:4px">({{ count($fired) }} dari 22)</span>
            </div>
            <div class="rules-list">
                @foreach($fired as $rule)
                @php
                $rc = match($rule['output']) {
                'rendah' => '#2B5D4F',
                'sedang' => '#8B5A1A',
                default => '#8B2020',
                };
                @endphp
                <div class="rule-item">
                    <span class="rule-out" style="color:{{ $rc }}">
                        → {{ ucfirst($rule['output']) }}
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

    <div style="text-align:center; margin-top:1.5rem">
        <a href="{{ route('diagnosis.index') }}" class="btn-secondary">
            Lihat semua riwayat →
        </a>
    </div>

</div>
@endsection