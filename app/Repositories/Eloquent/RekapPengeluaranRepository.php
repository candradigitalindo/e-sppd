<?php

namespace App\Repositories\Eloquent;

use App\Models\RekapPengeluaran;
use App\Repositories\Interfaces\RekapPengeluaranRepositoryInterface;

class RekapPengeluaranRepository extends BaseRepository implements RekapPengeluaranRepositoryInterface
{
    public function __construct(RekapPengeluaran $model)
    {
        parent::__construct($model);
    }

    public function getBySpj(string $spjId)
    {
        return $this->model->where('spj_id', $spjId)->get();
    }

    public function getTotalBySpj(string $spjId): array
    {
        $rekap = $this->model->where('spj_id', $spjId)->get();
        return [
            'total_rencana'   => (float) $rekap->sum('jumlah_rencana'),
            'total_realisasi' => (float) $rekap->sum('jumlah_realisasi'),
            'total_selisih'   => (float) $rekap->sum('selisih'),
        ];
    }

    public function bulkSave(string $spjId, array $items): void
    {
        $this->model->where('spj_id', $spjId)->delete();
        foreach ($items as $item) {
            $this->model->create(array_merge($item, ['spj_id' => $spjId]));
        }
    }
}
