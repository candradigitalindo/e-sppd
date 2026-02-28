<?php

namespace App\Repositories\Eloquent;

use App\Models\Spj;
use App\Repositories\Interfaces\SpjRepositoryInterface;

class SpjRepository extends BaseRepository implements SpjRepositoryInterface
{
    public function __construct(Spj $model)
    {
        parent::__construct($model);
    }

    public function getBySppd(string $sppdId)
    {
        return $this->model->where('sppd_id', $sppdId)->with('rekapPengeluaran')->first();
    }

    public function getByStatus(string $status)
    {
        return $this->model->where('status', $status)
            ->with(['sppd.pegawai'])
            ->latest()
            ->paginate(15);
    }

    public function generateNomor(): string
    {
        $tahun = date('Y');
        $bulan = date('m');
        $count = $this->model->whereYear('created_at', $tahun)->count() + 1;
        return sprintf('SPJ/%s/%s/%04d', $bulan, $tahun, $count);
    }

    public function updateStatus(string $id, string $status): bool
    {
        return (bool) $this->model->where('id', $id)->update(['status' => $status]);
    }

    public function getWithRekapPengeluaran(string $id)
    {
        return $this->model->with(['sppd.pegawai', 'rekapPengeluaran'])->findOrFail($id);
    }
}
