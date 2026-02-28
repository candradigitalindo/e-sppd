<?php

namespace App\Repositories\Eloquent;

use App\Models\KotaTujuan;
use App\Repositories\Interfaces\KotaTujuanRepositoryInterface;

class KotaTujuanRepository extends BaseRepository implements KotaTujuanRepositoryInterface
{
    public function __construct(KotaTujuan $model)
    {
        parent::__construct($model);
    }

    public function getByProvinsi(string $provinsi)
    {
        return $this->model->where('provinsi', $provinsi)->orderBy('nama')->get();
    }

    public function getByKategori(string $kategori)
    {
        return $this->model->where('kategori_biaya', $kategori)->orderBy('nama')->get();
    }

    public function getForDropdown()
    {
        return $this->model->orderBy('nama')->get(['id', 'nama', 'provinsi', 'kategori_biaya']);
    }
}
