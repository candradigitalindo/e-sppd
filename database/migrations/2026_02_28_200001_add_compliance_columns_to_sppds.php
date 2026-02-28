<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sppds', function (Blueprint $table) {
            // Jenis perjalanan dinas sesuai PMK
            $table->enum('jenis_perjalanan', [
                'dalam_negeri_biasa', // 524111 – Perjalanan Dinas Jabatan Dalam Negeri
                'dalam_kota',         // 524113 – Perjalanan Dinas Dalam Kota (≤8 jam)
                'paket_meeting',      // 524114 – Perjalanan Dinas Paket Meeting
                'luar_negeri',        // 524121 – Perjalanan Dinas Luar Negeri
                'pindah_tugas',       // 524119 – Perjalanan Pindah Tugas
            ])->default('dalam_negeri_biasa')->after('keperluan');

            // Kode MAK/rekening anggaran
            $table->string('kode_akun', 10)->nullable()->after('jenis_perjalanan')
                ->comment('Kode rekening belanja, cth: 524111');

            // Tanggal batas waktu penyerahan SPJ (5 hari kerja sejak tanggal_kembali)
            $table->date('deadline_spj')->nullable()->after('kode_akun');

            // Pejabat yang memberikan tugas (level 1 approval)
            $table->ulid('pejabat_pemberi_tugas_id')->nullable()->after('dibuat_oleh');

            // KPA – Kuasa Pengguna Anggaran (level 2 approval)
            $table->ulid('kpa_id')->nullable()->after('pejabat_pemberi_tugas_id');

            // Flag apakah menggunakan penginapan at-cost (bukan hotel)
            $table->boolean('penginapan_at_cost')->default(false)->after('kpa_id');
        });

        Schema::table('standar_biayas', function (Blueprint $table) {
            $table->string('kode_akun', 10)->nullable()->after('transport_lokal')
                ->comment('Kode MAK: 524111/524113/524114/524121');
        });

        Schema::table('program_kegiatans', function (Blueprint $table) {
            $table->string('kode_akun', 10)->nullable()->after('pagu_anggaran')
                ->comment('Kode rekening belanja program');
            $table->string('sumber_dana', 50)->nullable()->after('kode_akun')
                ->comment('APBN/APBD/BLU/dll');
        });
    }

    public function down(): void
    {
        Schema::table('sppds', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_perjalanan', 'kode_akun', 'deadline_spj',
                'pejabat_pemberi_tugas_id', 'kpa_id', 'penginapan_at_cost',
            ]);
        });

        Schema::table('standar_biayas', function (Blueprint $table) {
            $table->dropColumn('kode_akun');
        });

        Schema::table('program_kegiatans', function (Blueprint $table) {
            $table->dropColumn(['kode_akun', 'sumber_dana']);
        });
    }
};
