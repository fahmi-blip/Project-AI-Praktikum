<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Services\FuzzyMamdaniService;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function __construct(private FuzzyMamdaniService $fuzzy) {}

    // ── GET /diagnosis ── Form input
    public function create()
    {
        return view('diagnosis.create');
    }

    // ── POST /diagnosis ── Proses & simpan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pasien'   => 'nullable|string|max:100',
            'gula_darah'    => 'required|numeric|min:70|max:200',
            'tekanan_darah' => 'required|numeric|min:80|max:180',
            'bmi'           => 'required|numeric|min:15|max:45',
            'usia'          => 'required|integer|min:10|max:80',
        ], [
            'gula_darah.required'    => 'Kadar gula darah wajib diisi.',
            'gula_darah.min'         => 'Gula darah minimal 70 mg/dL.',
            'gula_darah.max'         => 'Gula darah maksimal 200 mg/dL.',
            'tekanan_darah.required' => 'Tekanan darah wajib diisi.',
            'tekanan_darah.min'      => 'Tekanan darah minimal 80 mmHg.',
            'tekanan_darah.max'      => 'Tekanan darah maksimal 180 mmHg.',
            'bmi.required'           => 'BMI wajib diisi.',
            'bmi.min'                => 'BMI minimal 15 kg/m².',
            'bmi.max'                => 'BMI maksimal 45 kg/m².',
            'usia.required'          => 'Usia wajib diisi.',
            'usia.min'               => 'Usia minimal 10 tahun.',
            'usia.max'               => 'Usia maksimal 80 tahun.',
        ]);

        // Proses fuzzy Mamdani
        $hasil = $this->fuzzy->diagnosa(
            (float) $validated['gula_darah'],
            (float) $validated['tekanan_darah'],
            (float) $validated['bmi'],
            (int)   $validated['usia'],
        );

        // Simpan ke database
        $diagnosis = Diagnosis::create([
            'nama_pasien'   => $validated['nama_pasien'] ?? 'Anonim',
            'gula_darah'    => $hasil['input']['gula'],
            'tekanan_darah' => $hasil['input']['tensi'],
            'bmi'           => $hasil['input']['bmi'],
            'usia'          => $hasil['input']['usia'],
            'skor_risiko'   => $hasil['skor'],
            'klasifikasi'   => $hasil['level'],
            'rekomendasi'   => $hasil['rekomendasi'],
            'detail_fuzzy'  => [
                'derajat'     => $hasil['derajat'],
                'agregasi'    => $hasil['agregasi'],
                'rules_fired' => $hasil['rules_fired'],
            ],
        ]);

        return redirect()->route('diagnosis.show', $diagnosis->id);
    }

    // ── GET /diagnosis/{id} ── Tampilkan hasil
    public function show(Diagnosis $diagnosis)
    {
        return view('diagnosis.show', compact('diagnosis'));
    }

    // ── GET /riwayat ── Daftar riwayat diagnosa
    public function index(Request $request)
    {
        $filter = $request->query('filter');

        $diagnoses = Diagnosis::when($filter, fn($q) => $q->byKlasifikasi($filter))
            ->latest()
            ->paginate(10);

        $stats = [
            'total'  => Diagnosis::count(),
            'rendah' => Diagnosis::byKlasifikasi('Rendah')->count(),
            'sedang' => Diagnosis::byKlasifikasi('Sedang')->count(),
            'tinggi' => Diagnosis::byKlasifikasi('Tinggi')->count(),
        ];

        return view('diagnosis.index', compact('diagnoses', 'stats', 'filter'));
    }

    // ── DELETE /diagnosis/{id} ── Hapus riwayat
    public function destroy(Diagnosis $diagnosis)
    {
        $diagnosis->delete();
        return redirect()->route('diagnosis.index')
            ->with('success', 'Data diagnosa berhasil dihapus.');
    }
}