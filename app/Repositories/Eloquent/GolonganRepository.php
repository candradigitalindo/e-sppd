<?php

namespace App\Repositories\Eloquent;

use App\Models\Golongan;
use App\Repositories\Interfaces\GolonganRepositoryInterface;

class GolonganRepository extends BaseRepository implements GolonganRepositoryInterface
{
    public function __construct(Golongan $model)
    {
        parent::__construct($model);
    }

    public function getForDropdown()
    {
        return $this->model->orderBy('kode')->get(['id', 'nama', 'kode']);
    }
}
