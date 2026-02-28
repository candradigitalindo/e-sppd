<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

// Models used in relationships are auto-resolved via namespace — no explicit imports needed

class Sppd extends Model
{
    use HasUlid, SoftDeletes;

    protected $table = 'sppds';

    protected $fillable = [
        'nomor', 'surat_tugas_id', 'pegawai_id', 'program_kegiatan_id',
        'kota_asal', 'kota_tujuan_id', 'tanggal_berangkat', 'tanggal_kembali',
        'transportasi', 'keperluan', 'status', 'dibuat_oleh', 'catatan',
        // Kolom regulasi PMK 113/2012
        'jenis_perjalanan', 'kode_akun', 'deadline_spj',
        'pejabat_pemberi_tugas_id', 'kpa_id', 'penginapan_at_cost',
    ];

    protected $casts = [
        'tanggal_berangkat'   => 'date',
        'tanggal_kembali'     => 'date',
        'deadline_spj'        => 'date',
        'penginapan_at_cost'  => 'boolean',
    ];

    public function suratTugas(): BelongsTo
    {
        return $this->belongsTo(SuratTugas::class);
    }

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

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function detailPerjalanan(): HasMany
    {
        return $this->hasMany(DetailPerjalanan::class);
    }

    public function riwayatPerjalanan(): HasMany
    {
        return $this->hasMany(RiwayatPerjalanan::class);
    }

    public function perhitunganBiaya(): HasMany
    {
        return $this->hasMany(PerhitunganBiaya::class);
    }

    public function pengajuanUangMuka(): HasOne
    {
        return $this->hasOne(PengajuanUangMuka::class);
    }

    public function laporanPerjalanan(): HasOne
    {
        return $this->hasOne(LaporanPerjalanan::class);
    }

    public function buktiPerjalanan(): HasMany
    {
        return $this->hasMany(BuktiPerjalanan::class);
    }

    public function spj(): HasOne
    {
        return $this->hasOne(Spj::class);
    }

    public function daftarPengeluaranRiil(): HasMany
    {
        return $this->hasMany(DaftarPengeluaranRiil::class);
    }

    public function pejabatPemberiTugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pejabat_pemberi_tugas_id');
    }

    public function kpa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kpa_id');
    }

    public function getLamaPerjalananAttribute(): int
    {
        if ($this->tanggal_berangkat && $this->tanggal_kembali) {
            return $this->tanggal_berangkat->diffInDays($this->tanggal_kembali) + 1;
        }
        return 0;
    }

    public function getTotalBiayaAttribute(): float
    {
        return (float) $this->perhitunganBiaya->sum('total');
    }
}
