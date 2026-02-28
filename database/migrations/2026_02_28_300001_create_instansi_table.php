<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instansi', function (Blueprint $table) {
            $table->id();

            // Identitas instansi
            $table->string('nama', 200)->default('');
            $table->string('singkatan', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('telepon', 50)->nullable();
            $table->string('fax', 50)->nullable();
            $table->string('website', 150)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();

            // Pejabat penandatangan
            $table->string('pejabat_nama', 150)->nullable();
            $table->string('pejabat_jabatan', 150)->nullable();
            $table->string('pejabat_nip', 30)->nullable();
            $table->string('pejabat_pangkat', 100)->nullable();

            // Logo: hingga 2 logo
            $table->string('logo1', 300)->nullable(); // path relatif dari storage/public
            $table->string('logo2', 300)->nullable();

            // Posisi logo: 'kiri', 'kanan', 'atas'
            $table->string('logo_posisi', 10)->default('kiri');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instansi');
    }
};
