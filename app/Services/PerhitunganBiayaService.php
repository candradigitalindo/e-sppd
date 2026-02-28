<?php

namespace App\Services;

use App\Models\Sppd;
use App\Models\StandarBiaya;
use App\Models\PerhitunganBiaya;
use Carbon\Carbon;

/**
 * Service perhitungan biaya perjalanan dinas
 * Sesuai PMK No. 113/PMK.05/2012 dan PMK Standar Biaya Masukan (SBM) tahunan
 *
 * Aturan utama:
 *  1. Uang harian hari berangkat & hari kembali = 40% (pasal tentang uang harian parsial)
 *  2. Uang harian hari penuh (menginap) = 100%
 *  3. Penginapan at-cost (tidak menginap di hotel) = 30% dari standar biaya penginapan
 *  4. Perjalanan dalam kota ≤8 jam tidak mendapat uang penginapan
 *  5. Komponen biaya disesuaikan dengan kode MAK
 */
class PerhitunganBiayaService
{
    // Kode MAK per jenis perjalanan
    const KODE_AKUN_MAP = [
        'dalam_negeri_biasa' => '524111',
        'dalam_kota'         => '524113',
        'paket_meeting'      => '524114',
        'luar_negeri'        => '524121',
        'pindah_tugas'       => '524119',
    ];

    /**
     * Hitung dan simpan seluruh komponen biaya secara otomatis.
     * Menghapus perhitungan lama sebelum menyimpan yang baru.
     *
     * @return PerhitunganBiaya[]
     */
    public function hitungOtomatis(Sppd $sppd): array
    {
        // Validasi
        if (!$sppd->tanggal_berangkat || !$sppd->tanggal_kembali) {
            throw new \InvalidArgumentException('Tanggal berangkat dan kembali wajib diisi.');
        }

        $golonganId   = $sppd->pegawai?->golongan_id;
        $kotaTujuanId = $sppd->kota_tujuan_id;
        $tahun        = $sppd->tanggal_berangkat->year;
        $jenis        = $sppd->jenis_perjalanan ?? 'dalam_negeri_biasa';
        $kodeAkun     = self::KODE_AKUN_MAP[$jenis] ?? '524111';
        $atCost       = (bool) $sppd->penginapan_at_cost;

        // Cari standar biaya yang sesuai
        $standar = null;
        if ($golonganId && $kotaTujuanId) {
            $standar = StandarBiaya::where('golongan_id', $golonganId)
                ->where('kota_tujuan_id', $kotaTujuanId)
                ->where('tahun', $tahun)
                ->first();
        }

        // Hitung struktur hari perjalanan
        $struktur = $this->hitungStrukturHari(
            $sppd->tanggal_berangkat,
            $sppd->tanggal_kembali,
            $jenis
        );

        // Hapus perhitungan lama
        PerhitunganBiaya::where('sppd_id', $sppd->id)->delete();

        $hasil = [];

        // === 1. UANG HARIAN ===
        if ($jenis !== 'dalam_kota' && $standar?->uang_harian > 0) {

            // Hari parsial (berangkat) — 40%
            if ($struktur['hari_parsial_awal'] > 0) {
                $satuan = $standar->uang_harian;
                $hasil[] = PerhitunganBiaya::create([
                    'sppd_id'      => $sppd->id,
                    'jenis'        => 'uang_harian',
                    'tipe_hari'    => 'parsial',
                    'persentase'   => 40.00,
                    'jumlah_hari'  => $struktur['hari_parsial_awal'],
                    'satuan_biaya' => $satuan,
                    'total'        => round($satuan * 0.40 * $struktur['hari_parsial_awal'], 2),
                    'keterangan'   => 'Uang harian hari keberangkatan (40%)',
                    'kode_akun'    => $kodeAkun,
                ]);
            }

            // Hari penuh — 100%
            if ($struktur['hari_penuh'] > 0) {
                $satuan = $standar->uang_harian;
                $hasil[] = PerhitunganBiaya::create([
                    'sppd_id'      => $sppd->id,
                    'jenis'        => 'uang_harian',
                    'tipe_hari'    => 'full',
                    'persentase'   => 100.00,
                    'jumlah_hari'  => $struktur['hari_penuh'],
                    'satuan_biaya' => $satuan,
                    'total'        => round($satuan * $struktur['hari_penuh'], 2),
                    'keterangan'   => 'Uang harian hari penuh (100%)',
                    'kode_akun'    => $kodeAkun,
                ]);
            }

            // Hari parsial (kembali) — 40% (hanya jika hari kembali berbeda dengan hari berangkat)
            if ($struktur['hari_parsial_akhir'] > 0) {
                $satuan = $standar->uang_harian;
                $hasil[] = PerhitunganBiaya::create([
                    'sppd_id'      => $sppd->id,
                    'jenis'        => 'uang_harian',
                    'tipe_hari'    => 'parsial',
                    'persentase'   => 40.00,
                    'jumlah_hari'  => $struktur['hari_parsial_akhir'],
                    'satuan_biaya' => $satuan,
                    'total'        => round($satuan * 0.40 * $struktur['hari_parsial_akhir'], 2),
                    'keterangan'   => 'Uang harian hari kepulangan (40%)',
                    'kode_akun'    => $kodeAkun,
                ]);
            }
        }

        // === 2. PENGINAPAN ===
        // Untuk dalam kota tidak ada penginapan
        if (!in_array($jenis, ['dalam_kota']) && $standar?->penginapan > 0) {
            $jumlahMalam  = $struktur['jumlah_malam'];
            $satuanHotel  = $standar->penginapan;

            if ($jumlahMalam > 0) {
                if ($atCost) {
                    // At-cost: hanya 30% dari standar (PMK)
                    $satuan = $satuanHotel * 0.30;
                    $hasil[] = PerhitunganBiaya::create([
                        'sppd_id'      => $sppd->id,
                        'jenis'        => 'penginapan',
                        'tipe_hari'    => 'full',
                        'persentase'   => 30.00,
                        'jumlah_hari'  => $jumlahMalam,
                        'satuan_biaya' => round($satuan, 2),
                        'total'        => round($satuan * $jumlahMalam, 2),
                        'at_cost'      => true,
                        'keterangan'   => 'Penginapan at-cost (30% standar — tidak menginap di hotel)',
                        'kode_akun'    => $kodeAkun,
                    ]);
                } else {
                    $hasil[] = PerhitunganBiaya::create([
                        'sppd_id'      => $sppd->id,
                        'jenis'        => 'penginapan',
                        'tipe_hari'    => 'full',
                        'persentase'   => 100.00,
                        'jumlah_hari'  => $jumlahMalam,
                        'satuan_biaya' => $satuanHotel,
                        'total'        => round($satuanHotel * $jumlahMalam, 2),
                        'at_cost'      => false,
                        'keterangan'   => 'Biaya penginapan sesuai standar',
                        'kode_akun'    => $kodeAkun,
                    ]);
                }
            }
        }

        // === 3. TRANSPORTASI ===
        if ($standar?->transportasi > 0) {
            // Transportasi PP (pergi-pulang = 2 kali)
            $hasil[] = PerhitunganBiaya::create([
                'sppd_id'      => $sppd->id,
                'jenis'        => 'transportasi',
                'tipe_hari'    => 'full',
                'persentase'   => 100.00,
                'jumlah_hari'  => 2,
                'satuan_biaya' => $standar->transportasi,
                'total'        => round($standar->transportasi * 2, 2),
                'keterangan'   => 'Transportasi pergi-pulang (PP)',
                'kode_akun'    => $kodeAkun,
            ]);
        }

        // === 4. TRANSPORT LOKAL ===
        if ($standar?->transport_lokal > 0 && $jenis !== 'dalam_kota') {
            $totalHari = $struktur['total_hari'];
            $hasil[] = PerhitunganBiaya::create([
                'sppd_id'      => $sppd->id,
                'jenis'        => 'transport_lokal',
                'tipe_hari'    => 'full',
                'persentase'   => 100.00,
                'jumlah_hari'  => $totalHari,
                'satuan_biaya' => $standar->transport_lokal,
                'total'        => round($standar->transport_lokal * $totalHari, 2),
                'keterangan'   => 'Transport lokal di kota tujuan',
                'kode_akun'    => $kodeAkun,
            ]);
        }

        // === 5. UANG REPRESENTASI (hanya untuk pejabat tertentu) ===
        if ($standar?->uang_representasi > 0 && $jenis !== 'dalam_kota') {
            $totalHari = $struktur['total_hari'];
            $hasil[] = PerhitunganBiaya::create([
                'sppd_id'      => $sppd->id,
                'jenis'        => 'uang_representasi',
                'tipe_hari'    => 'full',
                'persentase'   => 100.00,
                'jumlah_hari'  => $totalHari,
                'satuan_biaya' => $standar->uang_representasi,
                'total'        => round($standar->uang_representasi * $totalHari, 2),
                'keterangan'   => 'Uang representasi pejabat',
                'kode_akun'    => $kodeAkun,
            ]);
        }

        // Update kode_akun pada SPPD
        $sppd->update(['kode_akun' => $kodeAkun]);

        return $hasil;
    }

    /**
     * Hitung struktur hari perjalanan sesuai PMK.
     *
     * Contoh: berangkat tgl 1, kembali tgl 3
     *   - hari_parsial_awal  = 1 (tgl 1, 40%)
     *   - hari_penuh         = 1 (tgl 2, 100%)
     *   - hari_parsial_akhir = 1 (tgl 3, 40%)
     *   - jumlah_malam       = 2
     *
     * Jika berangkat dan kembali di hari sama:
     *   - hanya 1 hari, tipe full, 100%
     */
    public function hitungStrukturHari(
        Carbon $berangkat,
        Carbon $kembali,
        string $jenis = 'dalam_negeri_biasa'
    ): array {
        $totalHari = $berangkat->diffInDays($kembali) + 1;

        if ($totalHari === 1) {
            // Berangkat dan kembali di hari yang sama
            return [
                'total_hari'         => 1,
                'hari_parsial_awal'  => 0,
                'hari_penuh'         => 1,  // Tetap dapat 1 hari penuh jika 1 hari
                'hari_parsial_akhir' => 0,
                'jumlah_malam'       => 0,
            ];
        }

        if ($totalHari === 2) {
            // Berangkat hari 1, kembali hari 2 — keduanya parsial
            return [
                'total_hari'         => 2,
                'hari_parsial_awal'  => 1,
                'hari_penuh'         => 0,
                'hari_parsial_akhir' => 1,
                'jumlah_malam'       => 1,
            ];
        }

        // 3 hari atau lebih
        return [
            'total_hari'         => $totalHari,
            'hari_parsial_awal'  => 1,                    // Hari berangkat
            'hari_penuh'         => $totalHari - 2,       // Hari tengah
            'hari_parsial_akhir' => 1,                    // Hari kembali
            'jumlah_malam'       => $totalHari - 1,       // Malam menginap
        ];
    }

    /**
     * Hitung batas waktu penyerahan SPJ (5 hari kerja setelah tanggal kembali).
     * Hari Sabtu dan Minggu tidak dihitung.
     */
    public function hitungDeadlineSpj(Carbon $tanggalKembali): Carbon
    {
        $deadline  = $tanggalKembali->copy();
        $hariKerja = 0;

        while ($hariKerja < 5) {
            $deadline->addDay();
            // Skip hari Sabtu (6) dan Minggu (0)
            if (!in_array($deadline->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $hariKerja++;
            }
        }

        return $deadline;
    }
}
