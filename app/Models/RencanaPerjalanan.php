<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RencanaPerjalanan extends Model
{
    use HasUlid, SoftDeletes;

    protected $fillable = [
        'pegawai_id', 'kota_tujuan_id', 'program_kegiatan_id',
        'keperluan', 'tanggal_rencana', 'estimasi_hari',
        'status', 'catatan', 'disetujui_oleh', 'disetujui_at',
    ];

    protected $casts = [
        'tanggal_rencana' => 'date',
        'disetujui_at'    => 'datetime',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function kotaTujuan(): BelongsTo
    {
        return $this->belongsTo(KotaTujuan::class);
    }

    public function programKegiatan(): BelongsTo
    {
        return $this->belongsTo(ProgramKegiatan::class);
    }

    public function disetujuiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
