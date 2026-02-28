<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_perjalanans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('sppd_id');
            $table->date('tanggal');
            $table->string('kegiatan');
            $table->string('lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('sppd_id')->references('id')->on('sppds')->cascadeOnDelete();
        });

        Schema::create('riwayat_perjalanans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('sppd_id');
            $table->string('status', 50);
            $table->text('keterangan')->nullable();
            $table->ulid('user_id')->nullable();
            $table->timestamps();

            $table->foreign('sppd_id')->references('id')->on('sppds')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_perjalanans');
        Schema::dropIfExists('detail_perjalanans');
    }
};
