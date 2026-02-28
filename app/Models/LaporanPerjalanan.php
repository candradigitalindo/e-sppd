<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanPerjalanan extends Model
{
    use HasUlid, SoftDeletes;

    protected $fillable = [
        'sppd_id', 'isi_laporan', 'tanggal_laporan', 'status', 'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
    ];

    public function sppd(): BelongsTo
    {
        return $this->belongsTo(Sppd::class);
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function buktiPerjalanan(): HasMany
    {
        return $this->hasMany(BuktiPerjalanan::class, 'sppd_id', 'sppd_id');
    }
}
