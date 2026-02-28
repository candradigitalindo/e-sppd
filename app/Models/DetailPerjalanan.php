<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPerjalanan extends Model
{
    use HasUlid;

    protected $fillable = ['sppd_id', 'tanggal', 'kegiatan', 'lokasi', 'keterangan'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function sppd(): BelongsTo
    {
        return $this->belongsTo(Sppd::class);
    }
}
