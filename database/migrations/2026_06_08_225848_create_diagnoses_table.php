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
            $table->float('gula_darah');
            $table->float('tekanan_darah');
            $table->float('bmi');
            $table->integer('usia');
            $table->float('skor_risiko');
            $table->enum('klasifikasi', ['Rendah', 'Sedang', 'Tinggi']);
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