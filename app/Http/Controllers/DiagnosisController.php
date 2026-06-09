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
            'nama_pasien'      => 'nullable|string|max:100',
            'usia'             => 'required|integer|min:1|max:100',
            'berat_badan'      => 'required|numeric|min:20|max:200',
            'tinggi_badan'     => 'required|numeric|min:100|max:250',
            'gejala_3p'        => 'required|in:tidak,kadang,sering',
            'gejala_luka'      => 'required|in:tidak,kadang,sering',
            'riwayat_keluarga' => 'required|integer|min:0|max:10',
            'aktivitas_fisik'  => 'required|integer|min:0|max:7',
        ], [
            'usia.required'             => 'Usia wajib diisi.',
            'usia.min'                  => 'Usia minimal 1 tahun.',
            'usia.max'                  => 'Usia maksimal 100 tahun.',
            'berat_badan.required'      => 'Berat badan wajib diisi.',
            'berat_badan.min'           => 'Berat badan minimal 20 kg.',
            'berat_badan.max'           => 'Berat badan maksimal 200 kg.',
            'tinggi_badan.required'     => 'Tinggi badan wajib diisi.',
            'tinggi_badan.min'          => 'Tinggi badan minimal 100 cm.',
            'tinggi_badan.max'          => 'Tinggi badan maksimal 250 cm.',
            'gejala_3p.required'        => 'Gejala 3P wajib dipilih.',
            'gejala_luka.required'      => 'Gejala luka/kesemutan wajib dipilih.',
            'riwayat_keluarga.required' => 'Riwayat keluarga wajib diisi.',
            'aktivitas_fisik.required'  => 'Aktivitas fisik wajib diisi.',
        ]);

        // Proses fuzzy Mamdani
        $hasil = $this->fuzzy->diagnosa(
            (int)   $validated['usia'],
            (float) $validated['berat_badan'],
            (float) $validated['tinggi_badan'],
            $validated['gejala_3p'],
            $validated['gejala_luka'],
            (int)   $validated['riwayat_keluarga'],
            (int)   $validated['aktivitas_fisik'],
        );

        // Simpan ke database
        $diagnosis = Diagnosis::create([
            'nama_pasien'      => $validated['nama_pasien'] ?? 'Anonim',
            'usia'             => $hasil['input']['usia'],
            'berat_badan'      => $hasil['input']['berat'],
            'tinggi_badan'     => $hasil['input']['tinggi'],
            'bmi'              => $hasil['bmi'],
            'gejala_3p'        => $hasil['input']['gejala3p'],
            'gejala_luka'      => $hasil['input']['gejaleLuka'],
            'riwayat_keluarga' => $hasil['input']['riwayat'],
            'aktivitas_fisik'  => $hasil['input']['aktivitas'],
            'skor_risiko'      => $hasil['skor'],
            'klasifikasi'      => $hasil['level'],
            'rekomendasi'      => $hasil['rekomendasi'],
            'detail_fuzzy'     => [
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
            'total'        => Diagnosis::count(),
            'rendah'       => Diagnosis::byKlasifikasi('Rendah')->count(),
            'waspada'      => Diagnosis::byKlasifikasi('Waspada')->count(),
            'tinggi'       => Diagnosis::byKlasifikasi('Tinggi')->count(),
            'sangat_tinggi'=> Diagnosis::byKlasifikasi('Sangat Tinggi')->count(),
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