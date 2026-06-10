<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pasien')->default('Anonim');
            $table->integer('usia');
            $table->float('berat_badan');
            $table->float('tinggi_badan');
            $table->float('bmi');
            
            // Kolom gejala dan faktor risiko
            $table->string('gejala_3p');
            $table->string('gejala_luka');
            $table->integer('riwayat_keluarga');
            $table->integer('aktivitas_fisik');
            
            // Kolom hasil
            $table->float('skor_risiko');
            $table->string('klasifikasi');
            $table->text('rekomendasi');
            $table->json('detail_fuzzy')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};