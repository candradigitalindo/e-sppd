<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sppds', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nomor', 50)->unique();
            $table->ulid('surat_tugas_id')->nullable();
            $table->ulid('pegawai_id');
            $table->ulid('program_kegiatan_id')->nullable();
            $table->string('kota_asal', 100)->default('Makassar');
            $table->ulid('kota_tujuan_id')->nullable();
            $table->date('tanggal_berangkat');
            $table->date('tanggal_kembali');
            $table->integer('lama_perjalanan')->storedAs('(tanggal_kembali - tanggal_berangkat + 1)')->nullable();
            $table->enum('transportasi', ['pesawat', 'kereta', 'kapal', 'bus', 'kendaraan_dinas', 'kendaraan_pribadi'])->default('pesawat');
            $table->text('keperluan');
            $table->enum('status', [
                'draft', 'menunggu_approval', 'disetujui', 'berlangsung',
                'selesai', 'ditolak', 'dibatalkan',
            ])->default('draft');
            $table->ulid('dibuat_oleh')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('surat_tugas_id')->references('id')->on('surat_tugas')->nullOnDelete();
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->cascadeOnDelete();
            $table->foreign('kota_tujuan_id')->references('id')->on('kota_tujuans')->nullOnDelete();
            $table->foreign('program_kegiatan_id')->references('id')->on('program_kegiatans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sppds');
    }
};
