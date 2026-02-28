<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengajuanUangMuka extends Model
{
    use HasUlid, SoftDeletes;

    protected $fillable = [
        'sppd_id', 'pegawai_id', 'jumlah_pengajuan', 'tanggal_pengajuan',
        'status', 'catatan', 'disetujui_oleh', 'catatan_approval',
        'tanggal_approval', 'jumlah_dicairkan', 'tanggal_pencairan',
    ];

    protected $casts = [
        'jumlah_pengajuan'  => 'decimal:2',
        'jumlah_dicairkan'  => 'decimal:2',
        'tanggal_pengajuan' => 'date',
        'tanggal_approval'  => 'datetime',
        'tanggal_pencairan' => 'datetime',
    ];

    public function sppd(): BelongsTo
    {
        return $this->belongsTo(Sppd::class);
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function disetujuiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
