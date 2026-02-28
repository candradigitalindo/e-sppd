<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapPengeluaran extends Model
{
    use HasUlid;

    protected $fillable = [
        'spj_id', 'jenis', 'jumlah_rencana', 'jumlah_realisasi', 'keterangan',
    ];

    protected $casts = [
        'jumlah_rencana'   => 'decimal:2',
        'jumlah_realisasi' => 'decimal:2',
    ];

    public function spj(): BelongsTo
    {
        return $this->belongsTo(Spj::class);
    }

    public function getSelisihAttribute(): float
    {
        return (float) $this->jumlah_rencana - (float) $this->jumlah_realisasi;
    }
}
