<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Approval extends Model
{
    use HasUlid;

    protected $fillable = [
        'approvable_type', 'approvable_id', 'user_id',
        'urutan', 'jabatan_approver', 'status',
        'catatan', 'approved_at',
        // Multi-level approval
        'level', 'parent_id', 'batas_waktu', 'perbaikan',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'batas_waktu' => 'date',
        'perbaikan'   => 'boolean',
        'level'       => 'integer',
    ];

    public function approvable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Approval::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Approval::class, 'parent_id');
    }
}
