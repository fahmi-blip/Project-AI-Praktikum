@extends('layouts.app')
@section('title', 'Riwayat Diagnosa')

@section('content')
<div class="container">

    {{-- Header --}}
    <div class="page-header">
        <div>
            <h2 class="page-title">Riwayat Diagnosa</h2>
            <p class="page-sub">Seluruh data diagnosa yang tersimpan</p>
        </div>
        <a href="{{ route('diagnosis.create') }}" class="btn-run"
           style="width:auto; padding:10px 20px">
            + Diagnosa baru
        </a>
    </div>

    {{-- Stats --}}
    <div class="stats-grid" style="margin-bottom:1.5rem">
        <div class="stat-card">
            <div class="stat-num">{{ $stats['total'] }}</div>
            <div class="stat-label">Total diagnosa</div>
        </div>
        <div class="stat-card">
            <div class="stat-num" style="color:#2B5D4F">{{ $stats['rendah'] }}</div>
            <div class="stat-label">Risiko rendah</div>
        </div>
        <div class="stat-card">
            <div class="stat-num" style="color:#8B5A1A">{{ $stats['sedang'] }}</div>
            <div class="stat-label">Risiko sedang</div>
        </div>
        <div class="stat-card">
            <div class="stat-num" style="color:#8B2020">{{ $stats['tinggi'] }}</div>
            <div class="stat-label">Risiko tinggi</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <span class="filter-label">Filter:</span>
        @foreach([null => 'Semua', 'Rendah' => 'Rendah', 'Sedang' => 'Sedang', 'Tinggi' => 'Tinggi'] as $val => $lbl)
        <a href="{{ route('diagnosis.index', $val ? ['filter' => $val] : []) }}"
           class="filter-btn {{ $filter === $val ? 'active' : '' }}">
            {{ $lbl }}
        </a>
        @endforeach
    </div>

    {{-- Tabel atau empty state --}}
    @if($diagnoses->isEmpty())
    <div class="empty-state">
        <svg viewBox="0 0 24 24">
            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p>Belum ada data diagnosa.</p>
        <a href="{{ route('diagnosis.create') }}" class="btn-run"
           style="width:auto; padding:10px 20px; margin-top:12px">
            Mulai diagnosa pertama
        </a>
    </div>

    @else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Pasien</th>
                    <th>Gula (mg/dL)</th>
                    <th>Tensi (mmHg)</th>
                    <th>BMI</th>
                    <th>Usia</th>
                    <th>Skor</th>
                    <th>Klasifikasi</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($diagnoses as $d)
                <tr>
                    <td>{{ $d->nama_pasien }}</td>
                    <td>{{ $d->gula_darah }}</td>
                    <td>{{ $d->tekanan_darah }}</td>
                    <td>{{ $d->bmi }}</td>
                    <td>{{ $d->usia }}</td>
                    <td><strong>{{ $d->skor_risiko }}</strong></td>
                    <td>
                        <span class="risk-badge badge-{{ $d->warna }}">
                            <span class="badge-dot"></span>
                            {{ $d->klasifikasi }}
                        </span>
                    </td>
                    <td style="font-size:12px; color:var(--text3)">
                        {{ $d->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <div style="display:flex; gap:6px">
                            <a href="{{ route('diagnosis.show', $d->id) }}"
                               class="btn-xs">Detail</a>
                            <form action="{{ route('diagnosis.destroy', $d->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-xs btn-xs-danger">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrap">
        {{ $diagnoses->appends(request()->query())->links() }}
    </div>
    @endif

</div>
@endsection