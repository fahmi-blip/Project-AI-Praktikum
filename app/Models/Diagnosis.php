<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $fillable = [
        'nama_pasien',
        'usia',
        'berat_badan',
        'tinggi_badan',
        'bmi',
        'gejala_3p',
        'gejala_luka',
        'riwayat_keluarga',
        'aktivitas_fisik',
        'skor_risiko',
        'klasifikasi',
        'rekomendasi',
        'detail_fuzzy',
    ];

    protected $casts = [
        'detail_fuzzy' => 'array',
    ];

    // Filter berdasarkan klasifikasi
    public function scopeByKlasifikasi($query, string $level)
    {
        return $query->where('klasifikasi', $level);
    }

    // Warna badge otomatis
    public function getWarnaAttribute(): string
    {
        return match($this->klasifikasi) {
            'Rendah'       => 'green',
            'Waspada'      => 'amber',
            'Tinggi'       => 'orange',
            'Sangat Tinggi'=> 'red',
            default        => 'gray',
        };
    }

    // Label gejala
    public function getLabel3pAttribute(): string
    {
        return match($this->gejala_3p) {
            'tidak'  => 'Tidak Ada',
            'kadang' => 'Kadang-kadang',
            'sering' => 'Sering',
            default  => '-',
        };
    }

    public function getLabelLukaAttribute(): string
    {
        return match($this->gejala_luka) {
            'tidak'  => 'Tidak Ada',
            'kadang' => 'Kadang-kadang',
            'sering' => 'Sering',
            default  => '-',
        };
    }
}