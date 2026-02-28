<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KotaTujuan extends Model
{
    use HasUlid, SoftDeletes;

    protected $fillable = ['kode', 'nama', 'provinsi', 'kategori_biaya', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function standarBiayas(): HasMany
    {
        return $this->hasMany(StandarBiaya::class);
    }

    public function sppds(): HasMany
    {
        return $this->hasMany(Sppd::class);
    }
}
