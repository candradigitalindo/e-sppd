<?php

namespace App\Services;

use App\Models\UnitKerja;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk generate nomor dokumen tata naskah dinas
 * Sesuai Permendagri No. 54 Tahun 2009 tentang Tata Naskah Dinas
 *
 * Format SPT  : [Nomor Urut]/[Kode Klasifikasi]-[Kode Unit]/[Bulan Romawi]/[Tahun]
 * Format SPPD : [Nomor Urut]/SPPD/[Kode Unit]/[Bulan Romawi]/[Tahun]
 * Format SPJ  : [Nomor Urut]/SPJ/[Kode Unit]/[Bulan Romawi]/[Tahun]
 *
 * Contoh: 001/094/DINAS/II/2026  atau  001/SPPD/DINAS/II/2026
 */
class NomorDokumenService
{
    // Kode klasifikasi tata naskah dinas untuk perjalanan dinas
    const KODE_KLASIFIKASI = [
        'SPT'      => '094',
        'SPPD'     => 'SPPD',
        'SPJ'      => 'SPJ',
        'PENGAJUAN' => 'KU.02',
    ];

    const BULAN_ROMAWI = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
        7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
    ];

    /**
     * Generate nomor dokumen berikutnya.
     *
     * @param  string      $jenis       SPT|SPPD|SPJ|PENGAJUAN
     * @param  string|null $unitKerjaId ULID unit kerja
     * @param  int|null    $tahun       default: tahun sekarang
     * @param  int|null    $bulan       default: bulan sekarang
     * @return string
     */
    public function generate(
        string $jenis,
        ?string $unitKerjaId = null,
        ?int $tahun = null,
        ?int $bulan = null
    ): string {
        $tahun = $tahun ?? (int) date('Y');
        $bulan = $bulan ?? (int) date('n');

        $nomorUrut = $this->getAndIncrementNomor($jenis, $unitKerjaId, $tahun, $bulan);
        $kodeUnit  = $this->getKodeUnit($unitKerjaId);
        $kodeDok   = self::KODE_KLASIFIKASI[$jenis] ?? $jenis;
        $bulanRom  = self::BULAN_ROMAWI[$bulan] ?? (string) $bulan;

        return sprintf('%03d/%s/%s/%s/%d', $nomorUrut, $kodeDok, $kodeUnit, $bulanRom, $tahun);
    }

    /**
     * Ambil nomor urut berikutnya secara atomic (DB transaction + lock).
     */
    private function getAndIncrementNomor(
        string $jenis,
        ?string $unitKerjaId,
        int $tahun,
        int $bulan
    ): int {
        return DB::transaction(function () use ($jenis, $unitKerjaId, $tahun, $bulan) {
            $row = DB::table('nomor_dokumens')
                ->where('jenis', $jenis)
                ->where('unit_kerja_id', $unitKerjaId)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->lockForUpdate()
                ->first();

            if ($row) {
                $next = $row->nomor_terakhir + 1;
                DB::table('nomor_dokumens')
                    ->where('id', $row->id)
                    ->update(['nomor_terakhir' => $next, 'updated_at' => now()]);
            } else {
                $next = 1;
                DB::table('nomor_dokumens')->insert([
                    'jenis'        => $jenis,
                    'unit_kerja_id' => $unitKerjaId,
                    'tahun'        => $tahun,
                    'bulan'        => $bulan,
                    'nomor_terakhir' => 1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            return $next;
        });
    }

    /**
     * Ambil kode unit kerja dari database, default 'DINAS'.
     */
    private function getKodeUnit(?string $unitKerjaId): string
    {
        if (!$unitKerjaId) {
            return 'DINAS';
        }

        $unit = UnitKerja::find($unitKerjaId);
        if (!$unit) {
            return 'DINAS';
        }

        // Gunakan kode unit kerja (max 10 karakter, uppercase)
        return strtoupper(substr($unit->kode, 0, 10));
    }

    /**
     * Preview nomor berikutnya tanpa menyimpan ke database.
     */
    public function preview(
        string $jenis,
        ?string $unitKerjaId = null,
        ?int $tahun = null,
        ?int $bulan = null
    ): string {
        $tahun    = $tahun ?? (int) date('Y');
        $bulan    = $bulan ?? (int) date('n');
        $kodeUnit = $this->getKodeUnit($unitKerjaId);
        $kodeDok  = self::KODE_KLASIFIKASI[$jenis] ?? $jenis;
        $bulanRom = self::BULAN_ROMAWI[$bulan] ?? (string) $bulan;

        $row = DB::table('nomor_dokumens')
            ->where('jenis', $jenis)
            ->where('unit_kerja_id', $unitKerjaId)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        $next = $row ? ($row->nomor_terakhir + 1) : 1;

        return sprintf('%03d/%s/%s/%s/%d', $next, $kodeDok, $kodeUnit, $bulanRom, $tahun);
    }
}
