<?php

namespace App\Repositories\Eloquent;

use App\Models\Jabatan;
use App\Repositories\Interfaces\JabatanRepositoryInterface;

class JabatanRepository extends BaseRepository implements JabatanRepositoryInterface
{
    public function __construct(Jabatan $model)
    {
        parent::__construct($model);
    }

    public function getByLevel(string $level)
    {
        return $this->model->where('level', $level)->orderBy('nama')->get();
    }

    public function getForDropdown()
    {
        return $this->model->orderBy('nama')->get(['id', 'nama', 'kode', 'level']);
    }
}
