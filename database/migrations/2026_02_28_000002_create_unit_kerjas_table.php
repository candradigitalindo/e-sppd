<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_kerjas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('parent_id')->nullable();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->enum('tingkat', ['dinas', 'bidang', 'seksi', 'sub_bagian'])->default('dinas');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('unit_kerjas', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('unit_kerjas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_kerjas');
    }
};
