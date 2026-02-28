<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nip', 20)->unique()->nullable();
            $table->string('nama');
            $table->ulid('unit_kerja_id')->nullable();
            $table->ulid('jabatan_id')->nullable();
            $table->ulid('golongan_id')->nullable();
            $table->string('no_rekening', 30)->nullable();
            $table->string('nama_bank', 50)->nullable();
            $table->string('npwp', 20)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('unit_kerja_id')->references('id')->on('unit_kerjas')->nullOnDelete();
            $table->foreign('jabatan_id')->references('id')->on('jabatans')->nullOnDelete();
            $table->foreign('golongan_id')->references('id')->on('golongans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
