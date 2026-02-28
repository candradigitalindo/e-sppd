<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rencana_perjalanans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('pegawai_id');
            $table->ulid('kota_tujuan_id')->nullable();
            $table->ulid('program_kegiatan_id')->nullable();
            $table->string('keperluan');
            $table->date('tanggal_rencana');
            $table->integer('estimasi_hari')->default(1);
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dibatalkan'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->ulid('disetujui_oleh')->nullable();
            $table->timestamp('disetujui_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pegawai_id')->references('id')->on('pegawais')->cascadeOnDelete();
            $table->foreign('kota_tujuan_id')->references('id')->on('kota_tujuans')->nullOnDelete();
            $table->foreign('program_kegiatan_id')->references('id')->on('program_kegiatans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rencana_perjalanans');
    }
};
