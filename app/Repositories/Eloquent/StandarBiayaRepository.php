<?php

namespace App\Repositories\Eloquent;

use App\Models\StandarBiaya;
use App\Repositories\Interfaces\StandarBiayaRepositoryInterface;

class StandarBiayaRepository extends BaseRepository implements StandarBiayaRepositoryInterface
{
    public function __construct(StandarBiaya $model)
    {
        parent::__construct($model);
    }

    public function getByGolonganAndKota(string $golonganId, string $kotaId, int $tahun)
    {
        return $this->model->where('golongan_id', $golonganId)
            ->where('kota_tujuan_id', $kotaId)
            ->where('tahun', $tahun)
            ->with(['golongan', 'kotaTujuan'])
            ->first();
    }

    public function getByTahun(int $tahun)
    {
        return $this->model->where('tahun', $tahun)
            ->with(['golongan', 'kotaTujuan'])
            ->get();
    }

    public function getForCalculation(string $golonganId, string $kotaId, int $tahun)
    {
        return $this->model->where('golongan_id', $golonganId)
            ->where('kota_tujuan_id', $kotaId)
            ->where('tahun', $tahun)
            ->first(['uang_harian', 'penginapan', 'transportasi', 'uang_representasi', 'transport_lokal']);
    }
}
