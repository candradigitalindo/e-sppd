<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Instansi extends Model
{
    protected $table = 'instansi';

    protected $fillable = [
        'nama', 'singkatan', 'alamat', 'kota', 'telepon',
        'fax', 'website', 'email', 'kode_pos',
        'pejabat_nama', 'pejabat_jabatan', 'pejabat_nip', 'pejabat_pangkat',
        'logo1', 'logo2', 'logo_posisi',
    ];

    /**
     * Ambil record instansi pertama (singleton pattern).
     * Buat record kosong jika belum ada.
     */
    public static function getSingleton(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['nama' => 'NAMA INSTANSI', 'logo_posisi' => 'kiri']
        );
    }

    /**
     * URL publik logo1 (null jika belum diisi).
     */
    public function getLogo1UrlAttribute(): ?string
    {
        return $this->logo1 ? Storage::url($this->logo1) : null;
    }

    /**
     * URL publik logo2 (null jika belum diisi).
     */
    public function getLogo2UrlAttribute(): ?string
    {
        return $this->logo2 ? Storage::url($this->logo2) : null;
    }

    /**
     * Ekspos URL logo ke front-end.
     */
    protected $appends = ['logo1_url', 'logo2_url'];

    /**
     * Hindari url logo bocor saat disimpan di DB.
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Hapus file logo lama dari storage.
     */
    public function deleteLogo(int $slot): void
    {
        $col = "logo{$slot}";
        if ($this->{$col}) {
            Storage::delete($this->{$col});
            $this->{$col} = null;
            $this->save();
        }
    }
}
