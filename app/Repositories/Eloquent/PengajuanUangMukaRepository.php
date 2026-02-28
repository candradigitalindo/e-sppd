<?php

namespace App\Repositories\Eloquent;

use App\Models\PengajuanUangMuka;
use App\Repositories\Interfaces\PengajuanUangMukaRepositoryInterface;

class PengajuanUangMukaRepository extends BaseRepository implements PengajuanUangMukaRepositoryInterface
{
    public function __construct(PengajuanUangMuka $model)
    {
        parent::__construct($model);
    }

    public function getByStatus(string $status)
    {
        return $this->model->where('status', $status)
            ->with(['pegawai', 'sppd'])
            ->latest()
            ->paginate(15);
    }

    public function getByPegawai(string $pegawaiId)
    {
        return $this->model->where('pegawai_id', $pegawaiId)
            ->with(['sppd.kotaTujuan'])
            ->latest()
            ->get();
    }

    public function getBySppd(string $sppdId)
    {
        return $this->model->where('sppd_id', $sppdId)->with('pegawai')->first();
    }

    public function updateStatus(string $id, string $status, string $userId, ?string $catatan = null): bool
    {
        return (bool) $this->model->where('id', $id)->update([
            'status'          => $status,
            'disetujui_oleh'  => $userId,
            'catatan_approval' => $catatan,
            'tanggal_approval' => now(),
        ]);
    }

    public function getPending()
    {
        return $this->model->where('status', 'menunggu')
            ->with(['pegawai', 'sppd.kotaTujuan'])
            ->latest()
            ->get();
    }
}
