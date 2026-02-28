<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standar_biayas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('golongan_id');
            $table->ulid('kota_tujuan_id');
            $table->integer('tahun');
            $table->decimal('uang_harian', 15, 2)->default(0);
            $table->decimal('penginapan', 15, 2)->default(0);
            $table->decimal('transportasi', 15, 2)->default(0);
            $table->decimal('uang_representasi', 15, 2)->default(0);
            $table->decimal('transport_lokal', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['golongan_id', 'kota_tujuan_id', 'tahun']);
            $table->foreign('golongan_id')->references('id')->on('golongans')->cascadeOnDelete();
            $table->foreign('kota_tujuan_id')->references('id')->on('kota_tujuans')->cascadeOnDelete();
        });

        Schema::create('program_kegiatans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('unit_kerja_id')->nullable();
            $table->string('kode', 30);
            $table->string('nama');
            $table->integer('tahun');
            $table->decimal('pagu_anggaran', 20, 2)->default(0);
            $table->decimal('realisasi', 20, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('unit_kerja_id')->references('id')->on('unit_kerjas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kegiatans');
        Schema::dropIfExists('standar_biayas');
    }
};
