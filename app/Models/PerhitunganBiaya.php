<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerhitunganBiaya extends Model
{
    use HasUlid;

    protected $fillable = [
        'sppd_id', 'jenis', 'jumlah_hari', 'satuan_biaya', 'total', 'keterangan',
        // Kolom PMK 113/2012
        'tipe_hari', 'persentase', 'at_cost', 'kode_akun',
    ];

    protected $casts = [
        'satuan_biaya' => 'decimal:2',
        'total'        => 'decimal:2',
        'persentase'   => 'decimal:2',
        'at_cost'      => 'boolean',
    ];

    public function sppd(): BelongsTo
    {
        return $this->belongsTo(Sppd::class);
    }
}
