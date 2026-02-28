<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Daftar Pengeluaran Riil (DPR)
 *
 * Digunakan sebagai bukti pengeluaran at-cost untuk penginapan dan
 * biaya lain yang tidak dapat dibuktikan dengan kuitansi/faktur.
 * Sesuai PMK No. 113/PMK.05/2012 pasal tentang DPR.
 */
class DaftarPengeluaranRiil extends Model
{
    use HasUlid, SoftDeletes;

    protected $table = 'daftar_pengeluaran_riils';

    protected $fillable = [
        'sppd_id',
        'pegawai_id',
        'tanggal',
        'uraian',
        'jumlah',
        'jenis',
        'ditandatangani',
        'tanggal_ttd',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal'            => 'date',
        'jumlah'             => 'decimal:2',
        'ditandatangani'     => 'boolean',
        'tanggal_ttd'        => 'date',
        'tanggal_verifikasi' => 'date',
    ];

    public function sppd(): BelongsTo
    {
        return $this->belongsTo(Sppd::class);
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function diverifikasiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
