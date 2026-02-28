<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_perjalanans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('sppd_id')->unique();
            $table->text('isi_laporan');
            $table->date('tanggal_laporan');
            $table->enum('status', ['draft', 'selesai'])->default('draft');
            $table->ulid('dibuat_oleh')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sppd_id')->references('id')->on('sppds')->cascadeOnDelete();
        });

        Schema::create('bukti_perjalanans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('sppd_id');
            $table->enum('jenis', [
                'tiket_berangkat', 'tiket_pulang', 'boarding_pass',
                'kwitansi_hotel', 'kwitansi_transport', 'dokumen_lain',
            ]);
            $table->string('nama_file');
            $table->string('file_path');
            $table->string('mime_type', 50)->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('sppd_id')->references('id')->on('sppds')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_perjalanans');
        Schema::dropIfExists('laporan_perjalanans');
    }
};
