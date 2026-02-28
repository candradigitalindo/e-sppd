<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->morphs('approvable');
            $table->ulid('user_id')->nullable();
            $table->integer('urutan')->default(1);
            $table->string('jabatan_approver')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('user_id')->nullable();
            $table->string('action', 50);
            $table->string('module', 100);
            $table->json('data_before')->nullable();
            $table->json('data_after')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'module', 'created_at']);
        });

        Schema::create('backup_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->date('tanggal');
            $table->enum('status', ['sukses', 'gagal'])->default('sukses');
            $table->string('file_path')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->text('keterangan')->nullable();
            $table->ulid('dilakukan_oleh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('approvals');
    }
};
