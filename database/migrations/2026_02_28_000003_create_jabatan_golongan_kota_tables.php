<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jabatans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->enum('level', ['pimpinan_tinggi_madya', 'pimpinan_tinggi_pratama', 'administrator', 'pengawas', 'pelaksana', 'fungsional'])->default('pelaksana');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('golongans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('kode', 10)->unique();
            $table->string('nama');
            $table->string('ruang', 5)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('kota_tujuans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->string('provinsi');
            $table->enum('kategori_biaya', ['A', 'B', 'C', 'D'])->default('C');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kota_tujuans');
        Schema::dropIfExists('golongans');
        Schema::dropIfExists('jabatans');
    }
};
