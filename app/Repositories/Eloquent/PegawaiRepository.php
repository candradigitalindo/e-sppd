<?php

namespace App\Repositories\Eloquent;

use App\Models\Pegawai;
use App\Repositories\Interfaces\PegawaiRepositoryInterface;

class PegawaiRepository extends BaseRepository implements PegawaiRepositoryInterface
{
    public function __construct(Pegawai $model)
    {
        parent::__construct($model);
    }

    public function getByUnitKerja(string $unitKerjaId)
    {
        return $this->model->where('unit_kerja_id', $unitKerjaId)
            ->with(['jabatan', 'golongan', 'unitKerja'])
            ->get();
    }

    public function getByJabatan(string $jabatanId)
    {
        return $this->model->where('jabatan_id', $jabatanId)
            ->with(['jabatan', 'golongan', 'unitKerja'])
            ->get();
    }

    public function getByGolongan(string $golonganId)
    {
        return $this->model->where('golongan_id', $golonganId)
            ->with(['jabatan', 'golongan', 'unitKerja'])
            ->get();
    }

    public function getActiveWithRelations()
    {
        return $this->model->with(['jabatan', 'golongan', 'unitKerja'])
            ->where('is_active', true)
            ->orderBy('nama')
            ->get();
    }
}
