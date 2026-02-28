<?php

namespace App\Repositories\Eloquent;

use App\Models\ProgramKegiatan;
use App\Repositories\Interfaces\ProgramKegiatanRepositoryInterface;

class ProgramKegiatanRepository extends BaseRepository implements ProgramKegiatanRepositoryInterface
{
    public function __construct(ProgramKegiatan $model)
    {
        parent::__construct($model);
    }

    public function getByTahun(int $tahun)
    {
        return $this->model->where('tahun', $tahun)->with('unitKerja')->get();
    }

    public function getForDropdown(int $tahun)
    {
        return $this->model->where('tahun', $tahun)
            ->orderBy('kode')
            ->get(['id', 'kode', 'nama', 'pagu_anggaran']);
    }

    public function getTotalPagu(int $tahun): float
    {
        return (float) $this->model->where('tahun', $tahun)->sum('pagu_anggaran');
    }
}
