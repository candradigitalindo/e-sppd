<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BackupLog extends Model
{
    use HasUlid;

    protected $fillable = [
        'tanggal', 'status', 'file_path',
        'file_size', 'keterangan', 'dilakukan_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function dilakukanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dilakukan_oleh');
    }
}
