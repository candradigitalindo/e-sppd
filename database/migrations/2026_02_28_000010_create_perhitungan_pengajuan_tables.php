<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perhitungan_biayas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('sppd_id');
            $table->enum('jenis', [
                'uang_harian', 'penginapan', 'transportasi',
                'uang_representasi', 'transport_lokal',
            ]);
            $table->integer('jumlah_hari')->default(1);
            $table->decimal('satuan_biaya', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('sppd_id')->references('id')->on('sppds')->cascadeOnDelete();
        });

        Schema::create('pengajuan_uang_mukas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('sppd_id');
            $table->ulid('pegawai_id');
            $table->decimal('jumlah_pengajuan', 15, 2)->default(0);
            $table->date('tanggal_pengajuan');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dicairkan'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->ulid('disetujui_oleh')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->timestamp('tanggal_approval')->nullable();
            $table->decimal('jumlah_dicairkan', 15, 2)->nullable();
            $table->timestamp('tanggal_pencairan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sppd_id')->references('id')->on('sppds')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_uang_mukas');
        Schema::dropIfExists('perhitungan_biayas');
    }
};
