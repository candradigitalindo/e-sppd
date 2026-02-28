<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StandarBiaya extends Model
{
    use HasUlid, SoftDeletes;

    protected $fillable = [
        'golongan_id', 'kota_tujuan_id', 'tahun',
        'uang_harian', 'penginapan', 'transportasi',
        'uang_representasi', 'transport_lokal',
    ];

    protected $casts = [
        'uang_harian'       => 'decimal:2',
        'penginapan'        => 'decimal:2',
        'transportasi'      => 'decimal:2',
        'uang_representasi' => 'decimal:2',
        'transport_lokal'   => 'decimal:2',
    ];

    public function golongan(): BelongsTo
    {
        return $this->belongsTo(Golongan::class);
    }

    public function kotaTujuan(): BelongsTo
    {
        return $this->belongsTo(KotaTujuan::class);
    }
}
