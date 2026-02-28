<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spjs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('sppd_id')->unique();
            $table->string('nomor', 50)->unique();
            $table->date('tanggal');
            $table->decimal('total_rencana', 15, 2)->default(0);
            $table->decimal('total_realisasi', 15, 2)->default(0);
            $table->decimal('sisa_anggaran', 15, 2)->default(0);
            $table->enum('status', ['draft', 'selesai', 'disetujui'])->default('draft');
            $table->text('catatan')->nullable();
            $table->ulid('dibuat_oleh')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sppd_id')->references('id')->on('sppds')->cascadeOnDelete();
        });

        Schema::create('rekap_pengeluarans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('spj_id');
            $table->enum('jenis', [
                'uang_harian', 'penginapan', 'transportasi',
                'uang_representasi', 'transport_lokal',
            ]);
            $table->decimal('jumlah_rencana', 15, 2)->default(0);
            $table->decimal('jumlah_realisasi', 15, 2)->default(0);
            $table->decimal('selisih', 15, 2)->storedAs('jumlah_rencana - jumlah_realisasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('spj_id')->references('id')->on('spjs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_pengeluarans');
        Schema::dropIfExists('spjs');
    }
};
