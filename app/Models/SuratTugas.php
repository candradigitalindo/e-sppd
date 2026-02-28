<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratTugas extends Model
{
    use HasUlid, SoftDeletes;

    protected $table = 'surat_tugas';

    protected $fillable = [
        'nomor', 'tanggal', 'program_kegiatan_id', 'dasar_hukum',
        'keperluan', 'kota_asal', 'kota_tujuan_id',
        'tanggal_berangkat', 'tanggal_kembali',
        'status', 'dibuat_oleh', 'ditandatangani_oleh',
        'ditandatangani_at', 'catatan',
    ];

    protected $casts = [
        'tanggal'           => 'date',
        'tanggal_berangkat' => 'date',
        'tanggal_kembali'   => 'date',
        'ditandatangani_at' => 'datetime',
    ];

    public function programKegiatan(): BelongsTo
    {
        return $this->belongsTo(ProgramKegiatan::class);
    }

    public function kotaTujuan(): BelongsTo
    {
        return $this->belongsTo(KotaTujuan::class);
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function pegawais(): BelongsToMany
    {
        return $this->belongsToMany(Pegawai::class, 'surat_tugas_pegawai');
    }

    public function sppds(): HasMany
    {
        return $this->hasMany(Sppd::class);
    }
}
