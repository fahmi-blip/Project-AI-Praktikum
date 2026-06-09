<?php

namespace App\Services;

class FuzzyMamdaniService
{
    // ─────────────────────────────────────────────
    // FUNGSI KEANGGOTAAN
    // ─────────────────────────────────────────────

    private function trapMF(float $x, float $a, float $b, float $c, float $d): float
    {
        if ($x <= $a || $x >= $d) return 0.0;
        if ($x >= $b && $x <= $c) return 1.0;
        if ($x < $b) return ($x - $a) / ($b - $a);
        return ($d - $x) / ($d - $c);
    }

    private function triMF(float $x, float $a, float $b, float $c): float
    {
        if ($x <= $a || $x >= $c) return 0.0;
        if ($x <= $b) return ($x - $a) / ($b - $a);
        return ($c - $x) / ($c - $b);
    }

    // ─────────────────────────────────────────────
    // MAPPING INPUT KATEGORI → NILAI CRISP
    // ─────────────────────────────────────────────

    private function mapGejala(string $val): float
    {
        return match($val) {
            'tidak'  => 1.0,  // tengah himpunan Ringan (0-3)
            'kadang' => 5.0,  // tengah himpunan Sedang (4-6)
            'sering' => 8.5,  // tengah himpunan Berat  (7-10)
            default  => 0.0,
        };
    }

    // ─────────────────────────────────────────────
    // HITUNG BMI OTOMATIS
    // ─────────────────────────────────────────────

    public function hitungBMI(float $berat, float $tinggi): float
    {
        $tinggiMeter = $tinggi / 100;
        return round($berat / ($tinggiMeter * $tinggiMeter), 1);
    }

    // ─────────────────────────────────────────────
    // FUZZIFIKASI
    // ─────────────────────────────────────────────

    private function fuzzify(
        float $usia,
        float $bmi,
        float $gejala3p,
        float $gejaleLuka,
        float $riwayat,
        float $aktivitas
    ): array {
        return [
            'usia' => [
                'muda'     => $this->trapMF($usia,  1,   1,  25,  35),
                'parobaya' => $this->triMF ($usia, 28,  42,  56),
                'lansia'   => $this->trapMF($usia, 50,  60, 100, 100),
            ],
            'bmi' => [
                'kurus'     => $this->trapMF($bmi, 10,  10,  17,  18.5),
                'normal'    => $this->triMF ($bmi, 17, 21.5,  25),
                'overweight'=> $this->triMF ($bmi, 24,  27,   30),
                'obesitas'  => $this->trapMF($bmi, 29,  32,  50,  50),
            ],
            'gejala3p' => [
                'ringan' => $this->trapMF($gejala3p,  0,  0,  2,  4),
                'sedang' => $this->triMF ($gejala3p,  3,  5,  7),
                'berat'  => $this->trapMF($gejala3p,  6,  8, 10, 10),
            ],
            'gejaleLuka' => [
                'tidak_ada'    => $this->trapMF($gejaleLuka, 0, 0, 1, 3),
                'kadang_kadang'=> $this->triMF ($gejaleLuka, 2, 5, 8),
                'sering'       => $this->trapMF($gejaleLuka, 6, 8, 10, 10),
            ],
            'riwayat' => [
                'aman'        => $this->trapMF($riwayat, 0, 0, 2,  4),
                'rentan'      => $this->triMF ($riwayat, 3, 5,  7),
                'risiko_tinggi'=> $this->trapMF($riwayat, 6, 8, 10, 10),
            ],
            'aktivitas' => [
                'pasif'  => $this->trapMF($aktivitas, 0, 0, 1, 2),
                'sedang' => $this->triMF ($aktivitas, 1, 3, 5),
                'aktif'  => $this->trapMF($aktivitas, 4, 5, 7, 7),
            ],
        ];
    }

    // ─────────────────────────────────────────────
    // INFERENSI + AGREGASI MAX
    // ─────────────────────────────────────────────

    private function inferensi(array $mf): array
    {
        $u  = $mf['usia'];
        $b  = $mf['bmi'];
        $g  = $mf['gejala3p'];
        $l  = $mf['gejaleLuka'];
        $r  = $mf['riwayat'];
        $a  = $mf['aktivitas'];

        $rules = [
            // ── RENDAH ──────────────────────────────────────
            [min($u['muda'],     $b['normal'],     $a['aktif'],   $r['aman']),          'rendah'],
            [min($u['muda'],     $b['kurus'],      $g['ringan'],  $r['aman']),          'rendah'],
            [min($u['muda'],     $b['normal'],     $g['ringan']),                       'rendah'],
            [min($a['aktif'],    $b['normal'],     $r['aman'],    $g['ringan']),        'rendah'],
            [min($u['muda'],     $a['aktif'],      $g['ringan'],  $l['tidak_ada']),     'rendah'],
            [min($b['normal'],   $r['aman'],       $l['tidak_ada'], $g['ringan']),      'rendah'],

            // ── WASPADA ─────────────────────────────────────
            [min($u['parobaya'], $b['normal'],     $g['ringan'],  $r['aman']),          'waspada'],
            [min($u['muda'],     $b['overweight'], $g['ringan']),                       'waspada'],
            [min($u['parobaya'], $b['overweight'], $a['sedang']),                       'waspada'],
            [min($b['normal'],   $r['rentan'],     $g['sedang']),                       'waspada'],
            [min($u['muda'],     $r['rentan'],     $g['sedang'],  $a['sedang']),        'waspada'],
            [min($b['overweight'],$r['aman'],      $g['sedang'],  $a['sedang']),        'waspada'],
            [min($u['parobaya'], $b['normal'],     $r['rentan'],  $l['kadang_kadang']), 'waspada'],
            [min($a['pasif'],    $b['normal'],     $g['ringan'],  $r['aman']),          'waspada'],
            [min($u['muda'],     $b['obesitas'],   $g['ringan'],  $r['aman']),          'waspada'],

            // ── TINGGI ──────────────────────────────────────
            [min($u['lansia'],   $b['normal'],     $r['rentan'],  $g['sedang']),        'tinggi'],
            [min($u['parobaya'], $b['obesitas'],   $g['sedang']),                       'tinggi'],
            [min($b['overweight'],$r['risiko_tinggi'], $g['sedang']),                   'tinggi'],
            [min($u['lansia'],   $b['overweight'], $a['pasif'],   $r['rentan']),        'tinggi'],
            [min($g['berat'],    $b['overweight'], $r['rentan']),                       'tinggi'],
            [min($u['parobaya'], $r['risiko_tinggi'], $g['berat'], $a['pasif']),        'tinggi'],
            [min($l['sering'],   $b['overweight'], $r['rentan'],  $u['parobaya']),      'tinggi'],
            [min($u['lansia'],   $b['obesitas'],   $a['pasif']),                        'tinggi'],

            // ── SANGAT TINGGI ────────────────────────────────
            [min($u['lansia'],   $b['obesitas'],   $g['berat'],   $r['risiko_tinggi']), 'sangat_tinggi'],
            [min($g['berat'],    $r['risiko_tinggi'], $b['obesitas']),                  'sangat_tinggi'],
            [min($u['lansia'],   $g['berat'],      $l['sering'],  $r['risiko_tinggi']), 'sangat_tinggi'],
            [min($b['obesitas'], $g['berat'],      $a['pasif'],   $r['risiko_tinggi']), 'sangat_tinggi'],
            [min($u['lansia'],   $r['risiko_tinggi'], $a['pasif'], $l['sering']),       'sangat_tinggi'],
            [min($g['berat'],    $l['sering'],     $b['obesitas'], $a['pasif']),        'sangat_tinggi'],
        ];

        $agg   = ['rendah' => 0.0, 'waspada' => 0.0, 'tinggi' => 0.0, 'sangat_tinggi' => 0.0];
        $fired = [];

        foreach ($rules as [$w, $out]) {
            if ($w > 0) {
                $agg[$out] = max($agg[$out], $w);
                $fired[]   = ['output' => $out, 'bobot' => round($w, 4)];
            }
        }

        return ['agg' => $agg, 'fired' => $fired];
    }

    // ─────────────────────────────────────────────
    // DEFUZZIFIKASI — CENTROID
    // ─────────────────────────────────────────────

    private function defuzzify(array $agg): float
    {
        $num = 0.0;
        $den = 0.0;

        for ($i = 0; $i <= 200; $i++) {
            $x  = $i / 2.0; // 0..100 step 0.5
            $mu = max(
                min($agg['rendah'],        $this->trapMF($x,  0,  0, 15, 30)),
                min($agg['waspada'],       $this->triMF ($x, 25, 45, 60)),
                min($agg['tinggi'],        $this->triMF ($x, 55, 70, 85)),
                min($agg['sangat_tinggi'], $this->trapMF($x, 80, 90, 100, 100))
            );
            $num += $x * $mu;
            $den += $mu;
        }

        return $den > 0 ? round($num / $den, 2) : 0.0;
    }

    // ─────────────────────────────────────────────
    // KLASIFIKASI & REKOMENDASI
    // ─────────────────────────────────────────────

    private function klasifikasi(float $skor): array
    {
        if ($skor < 30) {
            return [
                'level'       => 'Rendah',
                'warna'       => 'green',
                'rekomendasi' => 'Profil kesehatan Anda saat ini tergolong baik. Pertahankan gaya hidup sehat dengan olahraga rutin minimal 30 menit per hari, konsumsi makanan bergizi seimbang, dan batasi makanan manis serta berlemak. Lakukan pemeriksaan kesehatan rutin setahun sekali.',
            ];
        } elseif ($skor < 60) {
            return [
                'level'       => 'Waspada',
                'warna'       => 'amber',
                'rekomendasi' => 'Beberapa faktor risiko diabetes terdeteksi pada profil Anda. Mulailah meningkatkan aktivitas fisik, kurangi konsumsi gula dan karbohidrat olahan, jaga berat badan ideal, dan pertimbangkan untuk memeriksakan kadar gula darah ke dokter atau puskesmas terdekat.',
            ];
        } elseif ($skor < 85) {
            return [
                'level'       => 'Tinggi',
                'warna'       => 'orange',
                'rekomendasi' => 'Profil risiko Anda menunjukkan kemungkinan diabetes yang cukup signifikan. Sangat disarankan untuk segera memeriksakan diri ke dokter untuk tes gula darah dan HbA1c. Ubah pola makan secara serius, tingkatkan aktivitas fisik, dan lakukan pemantauan kesehatan secara rutin.',
            ];
        } else {
            return [
                'level'       => 'Sangat Tinggi',
                'warna'       => 'red',
                'rekomendasi' => 'Profil risiko Anda sangat mengkhawatirkan. Segera konsultasikan ke dokter untuk pemeriksaan menyeluruh termasuk tes gula darah puasa, HbA1c, dan evaluasi komplikasi. Jangan tunda penanganan karena diabetes yang tidak tertangani dapat menyebabkan komplikasi serius.',
            ];
        }
    }

    // ─────────────────────────────────────────────
    // METODE UTAMA — dipanggil dari Controller
    // ─────────────────────────────────────────────

    public function diagnosa(
        int    $usia,
        float  $berat,
        float  $tinggi,
        string $gejala3p,
        string $gejaleLuka,
        int    $riwayat,
        int    $aktivitas
    ): array {
        $bmi      = $this->hitungBMI($berat, $tinggi);
        $g3p      = $this->mapGejala($gejala3p);
        $gLuka    = $this->mapGejala($gejaleLuka);

        $usia     = max(1,   min(100, $usia));
        $riwayat  = max(0,   min(10,  $riwayat));
        $aktivitas= max(0,   min(7,   $aktivitas));

        $mf          = $this->fuzzify($usia, $bmi, $g3p, $gLuka, $riwayat, $aktivitas);
        $inferResult = $this->inferensi($mf);
        $skor        = $this->defuzzify($inferResult['agg']);
        $klas        = $this->klasifikasi($skor);

        return [
            'skor'        => $skor,
            'bmi'         => $bmi,
            'level'       => $klas['level'],
            'warna'       => $klas['warna'],
            'rekomendasi' => $klas['rekomendasi'],
            'agregasi'    => $inferResult['agg'],
            'rules_fired' => $inferResult['fired'],
            'derajat'     => [
                'usia'       => $mf['usia'],
                'bmi'        => $mf['bmi'],
                'gejala3p'   => $mf['gejala3p'],
                'gejaleLuka' => $mf['gejaleLuka'],
                'riwayat'    => $mf['riwayat'],
                'aktivitas'  => $mf['aktivitas'],
            ],
            'input' => [
                'usia'       => $usia,
                'berat'      => $berat,
                'tinggi'     => $tinggi,
                'bmi'        => $bmi,
                'gejala3p'   => $gejala3p,
                'gejaleLuka' => $gejaleLuka,
                'riwayat'    => $riwayat,
                'aktivitas'  => $aktivitas,
            ],
        ];
    }
}