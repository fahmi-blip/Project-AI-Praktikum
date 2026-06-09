<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $fillable = [
        'nama_pasien',
        'gula_darah',
        'tekanan_darah',
        'bmi',
        'usia',
        'skor_risiko',
        'klasifikasi',
        'rekomendasi',
        'detail_fuzzy',
    ];

    protected $casts = [
        'detail_fuzzy' => 'array',
    ];

    // Filter berdasarkan klasifikasi risiko
    public function scopeByKlasifikasi($query, string $level)
    {
        return $query->where('klasifikasi', $level);
    }

    // Warna badge otomatis berdasarkan klasifikasi
    public function getWarnaAttribute(): string
    {
        return match($this->klasifikasi) {
            'Rendah' => 'green',
            'Sedang' => 'amber',
            'Tinggi' => 'red',
            default  => 'gray',
        };
    }
}
