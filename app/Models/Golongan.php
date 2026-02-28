<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Golongan extends Model
{
    use HasUlid, SoftDeletes;

    protected $fillable = ['kode', 'nama', 'ruang', 'keterangan'];

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class);
    }

    public function standarBiayas(): HasMany
    {
        return $this->hasMany(StandarBiaya::class);
    }
}
