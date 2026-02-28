<?php

namespace App\Repositories\Eloquent;

use App\Models\UnitKerja;
use App\Repositories\Interfaces\UnitKerjaRepositoryInterface;

class UnitKerjaRepository extends BaseRepository implements UnitKerjaRepositoryInterface
{
    public function __construct(UnitKerja $model)
    {
        parent::__construct($model);
    }

    public function getTree()
    {
        return $this->model->whereNull('parent_id')
            ->with('children.children.children')
            ->orderBy('nama')
            ->get();
    }

    public function getByParent(?string $parentId)
    {
        return $this->model->where('parent_id', $parentId)
            ->orderBy('nama')
            ->get();
    }

    public function getRoots()
    {
        return $this->model->whereNull('parent_id')
            ->orderBy('nama')
            ->get();
    }
}
