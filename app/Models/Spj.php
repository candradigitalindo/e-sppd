<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spj extends Model
{
    use HasUlid, SoftDeletes;

    protected $table = 'spjs';

    protected $fillable = [
        'sppd_id', 'nomor', 'tanggal', 'total_rencana',
        'total_realisasi', 'sisa_anggaran', 'status',
        'catatan', 'dibuat_oleh',
        // Deadline tracking
        'batas_penyerahan', 'terlambat', 'catatan_keterlambatan',
    ];

    protected $casts = [
        'tanggal'              => 'date',
        'total_rencana'        => 'decimal:2',
        'total_realisasi'      => 'decimal:2',
        'sisa_anggaran'        => 'decimal:2',
        'batas_penyerahan'     => 'date',
        'terlambat'            => 'boolean',
    ];

    public function sppd(): BelongsTo
    {
        return $this->belongsTo(Sppd::class);
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function rekapPengeluaran(): HasMany
    {
        return $this->hasMany(RekapPengeluaran::class);
    }
}
