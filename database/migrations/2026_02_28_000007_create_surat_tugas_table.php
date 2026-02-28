<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nomor', 50)->unique();
            $table->date('tanggal');
            $table->ulid('program_kegiatan_id')->nullable();
            $table->string('dasar_hukum')->nullable();
            $table->text('keperluan');
            $table->string('kota_asal', 100)->default('Makassar');
            $table->ulid('kota_tujuan_id')->nullable();
            $table->date('tanggal_berangkat');
            $table->date('tanggal_kembali');
            $table->enum('status', ['draft', 'aktif', 'selesai', 'dibatalkan'])->default('draft');
            $table->ulid('dibuat_oleh')->nullable();
            $table->ulid('ditandatangani_oleh')->nullable();
            $table->timestamp('ditandatangani_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('program_kegiatan_id')->references('id')->on('program_kegiatans')->nullOnDelete();
            $table->foreign('kota_tujuan_id')->references('id')->on('kota_tujuans')->nullOnDelete();
        });

        Schema::create('surat_tugas_pegawai', function (Blueprint $table) {
            $table->ulid('surat_tugas_id');
            $table->ulid('pegawai_id');
            $table->primary(['surat_tugas_id', 'pegawai_id']);

            $table->foreign('surat_tugas_id')->references('id')->on('surat_tugas')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_tugas_pegawai');
        Schema::dropIfExists('surat_tugas');
    }
};
