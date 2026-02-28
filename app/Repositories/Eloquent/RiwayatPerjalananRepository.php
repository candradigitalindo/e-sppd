<?php

namespace App\Repositories\Eloquent;

use App\Models\RiwayatPerjalanan;
use App\Repositories\Interfaces\RiwayatPerjalananRepositoryInterface;

class RiwayatPerjalananRepository extends BaseRepository implements RiwayatPerjalananRepositoryInterface
{
    public function __construct(RiwayatPerjalanan $model)
    {
        parent::__construct($model);
    }

    public function getBySppd(string $sppdId)
    {
        return $this->model->where('sppd_id', $sppdId)->orderBy('created_at')->get();
    }

    public function addRiwayat(string $sppdId, string $status, string $keterangan): void
    {
        $this->model->create([
            'sppd_id'    => $sppdId,
            'status'     => $status,
            'keterangan' => $keterangan,
        ]);
    }
}
