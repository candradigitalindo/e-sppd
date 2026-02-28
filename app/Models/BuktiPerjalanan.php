<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuktiPerjalanan extends Model
{
    use HasUlid;

    protected $fillable = [
        'sppd_id', 'jenis', 'nama_file', 'file_path',
        'mime_type', 'file_size', 'keterangan',
    ];

    public function sppd(): BelongsTo
    {
        return $this->belongsTo(Sppd::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
