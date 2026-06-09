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

            // Faktor Fisik Dasar
            $table->integer('usia');
            $table->float('berat_badan');
            $table->float('tinggi_badan');
            $table->float('bmi'); // dihitung otomatis

            // Faktor Gejala
            $table->enum('gejala_3p',   ['tidak', 'kadang', 'sering']); // haus, lapar, bak malam
            $table->enum('gejala_luka', ['tidak', 'kadang', 'sering']); // luka/kesemutan

            // Faktor Riwayat & Gaya Hidup
            $table->integer('riwayat_keluarga'); // 0-10
            $table->integer('aktivitas_fisik');  // 0-7 hari/minggu

            // Output
            $table->float('skor_risiko');
            $table->enum('klasifikasi', ['Rendah', 'Waspada', 'Tinggi', 'Sangat Tinggi']);
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