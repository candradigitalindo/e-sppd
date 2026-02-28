<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasUlid, SoftDeletes;

    protected $fillable = [
        'nip', 'nama', 'unit_kerja_id', 'jabatan_id', 'golongan_id',
        'no_rekening', 'nama_bank', 'npwp', 'no_hp', 'email', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function golongan(): BelongsTo
    {
        return $this->belongsTo(Golongan::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function sppds(): HasMany
    {
        return $this->hasMany(Sppd::class);
    }

    public function suratTugas(): BelongsToMany
    {
        return $this->belongsToMany(SuratTugas::class, 'surat_tugas_pegawai');
    }

    public function pengajuanUangMukas(): HasMany
    {
        return $this->hasMany(PengajuanUangMuka::class);
    }
}
