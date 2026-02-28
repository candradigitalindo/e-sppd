<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            // Level approval: 1 = Atasan Langsung/Pejabat Pengusul, 2 = KPA/PA
            $table->tinyInteger('level')
                ->default(1)
                ->after('id')
                ->comment('1=Atasan Langsung, 2=KPA/PA');

            // Approval setelah level ini disetujui
            $table->ulid('parent_id')
                ->nullable()
                ->after('level')
                ->comment('Approval level sebelumnya yang harus sudah disetujui');

            // jabatan_approver sudah ada di tabel asli — tidak perlu ditambah ulang

            // Tanggal batas waktu persetujuan (SLA internal)
            $table->date('batas_waktu')
                ->nullable()
                ->after('jabatan_approver');

            // Penolakan bisa dikembalikan untuk perbaikan (bukan final reject)
            $table->boolean('perbaikan')
                ->default(false)
                ->after('batas_waktu')
                ->comment('true = dikembalikan untuk perbaikan, false = ditolak final');
        });

        // Tabel SPJ tambahkan deadline tracking
        Schema::table('spjs', function (Blueprint $table) {
            // Batas penyerahan SPJ: 5 hari kerja setelah tanggal kembali (PMK 113/2012 Ps. 17)
            $table->date('batas_penyerahan')
                ->nullable()
                ->after('tanggal')
                ->comment('Batas 5 hari kerja sejak tanggal kembali');

            // Apakah SPJ terlambat diserahkan?
            $table->boolean('terlambat')
                ->default(false)
                ->after('batas_penyerahan');

            // Catatan keterlambatan
            $table->text('catatan_keterlambatan')
                ->nullable()
                ->after('terlambat');
        });
    }

    public function down(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropColumn([
                'level', 'parent_id', 'jabatan_approver',
                'batas_waktu', 'perbaikan',
            ]);
        });

        Schema::table('spjs', function (Blueprint $table) {
            $table->dropColumn([
                'batas_penyerahan', 'terlambat', 'catatan_keterlambatan',
            ]);
        });
    }
};
