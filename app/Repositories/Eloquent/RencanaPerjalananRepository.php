<?php

namespace App\Repositories\Eloquent;

use App\Models\RencanaPerjalanan;
use App\Repositories\Interfaces\RencanaPerjalananRepositoryInterface;

class RencanaPerjalananRepository extends BaseRepository implements RencanaPerjalananRepositoryInterface
{
    public function __construct(RencanaPerjalanan $model)
    {
        parent::__construct($model);
    }

    public function getByPegawai(string $pegawaiId)
    {
        return $this->model->where('pegawai_id', $pegawaiId)
            ->with(['pegawai', 'kotaTujuan', 'programKegiatan'])
            ->latest()
            ->get();
    }

    public function getByStatus(string $status)
    {
        return $this->model->where('status', $status)
            ->with(['pegawai', 'kotaTujuan'])
            ->latest()
            ->paginate(15);
    }

    public function getPending()
    {
        return $this->model->where('status', 'menunggu')
            ->with(['pegawai', 'kotaTujuan'])
            ->latest()
            ->get();
    }

    public function updateStatus(string $id, string $status, ?string $catatan = null): bool
    {
        return (bool) $this->model->where('id', $id)->update([
            'status'  => $status,
            'catatan' => $catatan,
        ]);
    }
}
