<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom ke perhitungan_biayas untuk support 40% uang harian
        Schema::table('perhitungan_biayas', function (Blueprint $table) {
            // Tipe hari: full = 100%, parsial = 40% (hari berangkat/pulang per PMK)
            $table->enum('tipe_hari', ['full', 'parsial'])
                ->default('full')
                ->after('jenis')
                ->comment('full=100%, parsial=40% hari berangkat/pulang');

            // Persentase yang diterapkan (100 atau 40)
            $table->decimal('persentase', 5, 2)
                ->default(100.00)
                ->after('tipe_hari')
                ->comment('Persentase uang harian: 100% atau 40%');

            // Untuk penginapan: apakah at-cost (30% dari standar)?
            $table->boolean('at_cost')
                ->default(false)
                ->after('persentase')
                ->comment('Penginapan tidak di hotel = 30% standar');

            // Kode akun per komponen biaya
            $table->string('kode_akun', 10)->nullable()->after('keterangan');
        });

        // Tabel Daftar Pengeluaran Riil (DPR) — PMK 113/2012 Ps. 22
        Schema::create('daftar_pengeluaran_riils', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('sppd_id');
            $table->ulid('pegawai_id');
            $table->date('tanggal');
            $table->text('uraian');
            $table->decimal('jumlah', 15, 2)->default(0);
            // Jenis pengeluaran yang tidak ada bukti kuitansi resmi
            $table->enum('jenis', [
                'transport_lokal',
                'uang_harian_parsial',
                'biaya_lain',
            ])->default('biaya_lain');
            // Pernyataan telah ditandatangani oleh pegawai
            $table->boolean('ditandatangani')->default(false);
            $table->timestamp('tanggal_ttd')->nullable();
            // Diverifikasi oleh PPK/Bendahara
            $table->ulid('diverifikasi_oleh')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sppd_id')->references('id')->on('sppds')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->cascadeOnDelete();
        });

        // Tabel nomor urut dokumen per jenis+unit_kerja+tahun+bulan
        // Digunakan oleh NomorDokumenService untuk auto-generate nomor tata naskah dinas
        Schema::create('nomor_dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('jenis', 20)->comment('SPT, SPPD, SPJ, PENGAJUAN');
            $table->ulid('unit_kerja_id')->nullable();
            $table->smallInteger('tahun');
            $table->tinyInteger('bulan');
            $table->integer('nomor_terakhir')->default(0);
            $table->timestamps();

            $table->unique(['jenis', 'unit_kerja_id', 'tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::table('perhitungan_biayas', function (Blueprint $table) {
            $table->dropColumn(['tipe_hari', 'persentase', 'at_cost', 'kode_akun']);
        });

        Schema::dropIfExists('daftar_pengeluaran_riils');
        Schema::dropIfExists('nomor_dokumens');
    }
};
