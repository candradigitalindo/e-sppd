<?php

namespace App\Repositories\Eloquent;

use App\Models\LaporanPerjalanan;
use App\Models\Sppd;
use App\Repositories\Interfaces\LaporanPerjalananRepositoryInterface;

class LaporanPerjalananRepository extends BaseRepository implements LaporanPerjalananRepositoryInterface
{
    public function __construct(LaporanPerjalanan $model)
    {
        parent::__construct($model);
    }

    public function getBySppd(string $sppdId)
    {
        return $this->model->where('sppd_id', $sppdId)->with(['dibuatOleh', 'buktiPerjalanan'])->first();
    }

    public function getByPegawai(string $pegawaiId)
    {
        return $this->model->whereHas('sppd', function ($q) use ($pegawaiId) {
            $q->where('pegawai_id', $pegawaiId);
        })->with(['sppd.kotaTujuan'])->latest()->get();
    }

    public function getDashboardStats(int $tahun): array
    {
        $total       = Sppd::whereYear('tanggal_berangkat', $tahun)->count();
        $selesai     = Sppd::whereYear('tanggal_berangkat', $tahun)->where('status', 'selesai')->count();
        $berlangsung = Sppd::whereYear('tanggal_berangkat', $tahun)->where('status', 'berlangsung')->count();
        $disetujui   = Sppd::whereYear('tanggal_berangkat', $tahun)->where('status', 'disetujui')->count();

        return compact('total', 'selesai', 'berlangsung', 'disetujui');
    }
}
