<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasUlid;

    protected $fillable = [
        'user_id', 'action', 'module',
        'data_before', 'data_after',
        'ip_address', 'user_agent',
    ];

    protected $casts = [
        'data_before' => 'array',
        'data_after'  => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
