<?php

namespace App\Repositories\Eloquent;

use App\Models\PerhitunganBiaya;
use App\Models\Sppd;
use App\Models\StandarBiaya;
use App\Repositories\Interfaces\PerhitunganBiayaRepositoryInterface;

class PerhitunganBiayaRepository extends BaseRepository implements PerhitunganBiayaRepositoryInterface
{
    public function __construct(PerhitunganBiaya $model)
    {
        parent::__construct($model);
    }

    public function getBySppd(string $sppdId)
    {
        return $this->model->where('sppd_id', $sppdId)->get();
    }

    public function getByJenis(string $sppdId, string $jenis)
    {
        return $this->model->where('sppd_id', $sppdId)->where('jenis', $jenis)->first();
    }

    public function getTotalBySppd(string $sppdId): float
    {
        return (float) $this->model->where('sppd_id', $sppdId)->sum('total');
    }

    public function hitungOtomatis(string $sppdId): array
    {
        $sppd = Sppd::with(['pegawai.golongan', 'kotaTujuan'])->findOrFail($sppdId);
        $jumlahHari = $sppd->tanggal_berangkat->diffInDays($sppd->tanggal_kembali) + 1;
        $tahun      = (int) $sppd->tanggal_berangkat->format('Y');

        $sbm = StandarBiaya::where('golongan_id', $sppd->pegawai->golongan_id)
            ->where('kota_tujuan_id', $sppd->kota_tujuan_id)
            ->where('tahun', $tahun)
            ->first();

        if (! $sbm) {
            return [];
        }

        return [
            ['jenis' => 'uang_harian',    'jumlah_hari' => $jumlahHari, 'satuan_biaya' => $sbm->uang_harian,       'total' => $jumlahHari * $sbm->uang_harian],
            ['jenis' => 'penginapan',      'jumlah_hari' => $jumlahHari, 'satuan_biaya' => $sbm->penginapan,         'total' => $jumlahHari * $sbm->penginapan],
            ['jenis' => 'transportasi',    'jumlah_hari' => 1,          'satuan_biaya' => $sbm->transportasi,        'total' => $sbm->transportasi],
            ['jenis' => 'transport_lokal', 'jumlah_hari' => $jumlahHari, 'satuan_biaya' => $sbm->transport_lokal,    'total' => $jumlahHari * $sbm->transport_lokal],
        ];
    }

    public function bulkSave(string $sppdId, array $items): void
    {
        $this->model->where('sppd_id', $sppdId)->delete();
        foreach ($items as $item) {
            $this->model->create(array_merge($item, ['sppd_id' => $sppdId]));
        }
    }
}
