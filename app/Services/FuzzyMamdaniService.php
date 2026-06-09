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
    // FUZZIFIKASI
    // ─────────────────────────────────────────────

    private function fuzzify(float $gula, float $tensi, float $bmi, float $usia): array
    {
        return [
            'gula' => [
                'normal'   => $this->trapMF($gula,  70,  70,  90, 110),
                'pra'      => $this->triMF ($gula,  95, 118, 140),
                'diabetes' => $this->trapMF($gula, 126, 155, 200, 200),
            ],
            'tensi' => [
                'normal' => $this->trapMF($tensi,  80,  80, 110, 125),
                'sedang' => $this->triMF ($tensi, 115, 135, 155),
                'tinggi' => $this->trapMF($tensi, 140, 160, 180, 180),
            ],
            'bmi' => [
                'kurus'  => $this->trapMF($bmi,  15,  15,  17,  19),
                'normal' => $this->trapMF($bmi,  18,  20,  23,  25),
                'lebih'  => $this->triMF ($bmi,  24,  27,  31),
                'obese'  => $this->trapMF($bmi,  29,  33,  45,  45),
            ],
            'usia' => [
                'muda'   => $this->trapMF($usia,  10,  10,  25,  35),
                'dewasa' => $this->triMF ($usia,  28,  42,  56),
                'lansia' => $this->trapMF($usia,  50,  62,  80,  80),
            ],
        ];
    }

    // ─────────────────────────────────────────────
    // INFERENSI (22 RULES) + AGREGASI MAX
    // ─────────────────────────────────────────────

    private function inferensi(array $mf): array
    {
        $g = $mf['gula'];
        $t = $mf['tensi'];
        $b = $mf['bmi'];
        $u = $mf['usia'];

        $rules = [
            // RENDAH
            [min($g['normal'], $t['normal'], $b['normal']), 'rendah'],
            [min($g['normal'], $t['normal'], $b['kurus']),  'rendah'],
            [min($g['normal'], $t['normal'], $u['muda']),   'rendah'],
            [min($g['normal'], $b['normal'], $u['muda']),   'rendah'],
            [min($g['normal'], $b['kurus']),                'rendah'],
            [min($g['normal'], $t['normal']),               'rendah'],
            // SEDANG
            [min($g['pra'], $t['normal'], $b['normal']),    'sedang'],
            [min($g['pra'], $b['lebih']),                   'sedang'],
            [min($g['normal'], $t['sedang'], $b['lebih']),  'sedang'],
            [min($g['normal'], $t['sedang'], $u['dewasa']), 'sedang'],
            [min($g['pra'], $u['dewasa']),                  'sedang'],
            [min($g['normal'], $b['obese'], $u['muda']),    'sedang'],
            [min($g['pra'], $t['sedang']),                  'sedang'],
            [min($g['normal'], $t['tinggi'], $b['normal']), 'sedang'],
            // TINGGI
            [$g['diabetes'],                                'tinggi'],
            [min($g['pra'], $b['obese']),                   'tinggi'],
            [min($g['pra'], $t['tinggi']),                  'tinggi'],
            [min($g['pra'], $u['lansia']),                  'tinggi'],
            [min($g['normal'], $t['tinggi'], $b['obese']),  'tinggi'],
            [min($g['normal'], $b['obese'], $u['lansia']),  'tinggi'],
            [min($g['pra'], $b['obese'], $u['lansia']),     'tinggi'],
            [min($g['pra'], $t['tinggi'], $u['lansia']),    'tinggi'],
        ];

        $agg   = ['rendah' => 0.0, 'sedang' => 0.0, 'tinggi' => 0.0];
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
            $x  = $i / 2.0;
            $mu = max(
                min($agg['rendah'], $this->trapMF($x,  0,  0, 20, 35)),
                min($agg['sedang'], $this->triMF ($x, 25, 50, 65)),
                min($agg['tinggi'], $this->trapMF($x, 55, 75, 100, 100))
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
        if ($skor < 35) {
            return [
                'level'       => 'Rendah',
                'warna'       => 'green',
                'rekomendasi' => 'Kondisi Anda saat ini tergolong baik. Pertahankan pola makan sehat, olahraga rutin minimal 30 menit per hari, dan lakukan pemeriksaan kesehatan setahun sekali.',
            ];
        } elseif ($skor < 65) {
            return [
                'level'       => 'Sedang',
                'warna'       => 'amber',
                'rekomendasi' => 'Beberapa indikator Anda berada di zona pra-diabetes. Kurangi konsumsi gula dan karbohidrat olahan, tingkatkan aktivitas fisik, dan konsultasikan ke dokter setiap 3–6 bulan.',
            ];
        } else {
            return [
                'level'       => 'Tinggi',
                'warna'       => 'red',
                'rekomendasi' => 'Risiko diabetes Anda tergolong tinggi. Segera konsultasikan ke dokter untuk pemeriksaan HbA1c dan penanganan lebih lanjut.',
            ];
        }
    }

    // ─────────────────────────────────────────────
    // METODE UTAMA — dipanggil dari Controller
    // ─────────────────────────────────────────────

    public function diagnosa(float $gula, float $tensi, float $bmi, float $usia): array
    {
        $gula  = max(70,  min(200, $gula));
        $tensi = max(80,  min(180, $tensi));
        $bmi   = max(15,  min(45,  $bmi));
        $usia  = max(10,  min(80,  $usia));

        $mf          = $this->fuzzify($gula, $tensi, $bmi, $usia);
        $inferResult = $this->inferensi($mf);
        $skor        = $this->defuzzify($inferResult['agg']);
        $klas        = $this->klasifikasi($skor);

        return [
            'skor'        => $skor,
            'level'       => $klas['level'],
            'warna'       => $klas['warna'],
            'rekomendasi' => $klas['rekomendasi'],
            'agregasi'    => $inferResult['agg'],
            'rules_fired' => $inferResult['fired'],
            'derajat'     => [
                'gula'  => $mf['gula'],
                'tensi' => $mf['tensi'],
                'bmi'   => $mf['bmi'],
                'usia'  => $mf['usia'],
            ],
            'input' => [
                'gula'  => $gula,
                'tensi' => $tensi,
                'bmi'   => $bmi,
                'usia'  => $usia,
            ],
        ];
    }
}