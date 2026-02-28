<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramKegiatan extends Model
{
    use HasUlid, SoftDeletes;

    protected $fillable = [
        'unit_kerja_id', 'kode', 'nama', 'tahun',
        'pagu_anggaran', 'realisasi', 'keterangan', 'is_active',
    ];

    protected $casts = [
        'pagu_anggaran' => 'decimal:2',
        'realisasi'     => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function sppds(): HasMany
    {
        return $this->hasMany(Sppd::class);
    }

    public function suratTugas(): HasMany
    {
        return $this->hasMany(SuratTugas::class);
    }
}
