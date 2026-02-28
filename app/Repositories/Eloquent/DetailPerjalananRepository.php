<?php

namespace App\Repositories\Eloquent;

use App\Models\DetailPerjalanan;
use App\Repositories\Interfaces\DetailPerjalananRepositoryInterface;

class DetailPerjalananRepository extends BaseRepository implements DetailPerjalananRepositoryInterface
{
    public function __construct(DetailPerjalanan $model)
    {
        parent::__construct($model);
    }

    public function getBySppd(string $sppdId)
    {
        return $this->model->where('sppd_id', $sppdId)->orderBy('tanggal')->get();
    }

    public function bulkCreate(string $sppdId, array $details): void
    {
        $this->model->where('sppd_id', $sppdId)->delete();
        foreach ($details as $detail) {
            $this->model->create(array_merge($detail, ['sppd_id' => $sppdId]));
        }
    }
}
