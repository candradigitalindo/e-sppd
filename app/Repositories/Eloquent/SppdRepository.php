<?php

namespace App\Repositories\Eloquent;

use App\Models\Sppd;
use App\Repositories\Interfaces\SppdRepositoryInterface;

class SppdRepository extends BaseRepository implements SppdRepositoryInterface
{
    public function __construct(Sppd $model)
    {
        parent::__construct($model);
    }

    public function getByPegawai(string $pegawaiId)
    {
        return $this->model->where('pegawai_id', $pegawaiId)
            ->with(['pegawai', 'kotaTujuan', 'suratTugas'])
            ->latest()
            ->paginate(15);
    }

    public function getByStatus(string $status)
    {
        return $this->model->where('status', $status)
            ->with(['pegawai', 'kotaTujuan'])
            ->latest()
            ->paginate(15);
    }

    public function getBySuratTugas(string $suratTugasId)
    {
        return $this->model->where('surat_tugas_id', $suratTugasId)
            ->with(['pegawai', 'kotaTujuan'])
            ->get();
    }

    public function generateNomor(): string
    {
        $tahun = date('Y');
        $bulan = date('m');
        $count = $this->model->whereYear('created_at', $tahun)->count() + 1;
        return sprintf('SPPD/%s/%s/%04d', $bulan, $tahun, $count);
    }

    public function updateStatus(string $id, string $status): bool
    {
        return (bool) $this->model->where('id', $id)->update(['status' => $status]);
    }

    public function getWithRelations(string $id)
    {
        return $this->model->with([
            'pegawai.jabatan',
            'pegawai.golongan',
            'pegawai.unitKerja',
            'kotaTujuan',
            'suratTugas',
            'detailPerjalanan',
            'perhitunganBiaya',
        ])->findOrFail($id);
    }

    public function getForMonitoring(array $filters = [])
    {
        $query = $this->model->with(['pegawai', 'kotaTujuan']);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['pegawai_id'])) {
            $query->where('pegawai_id', $filters['pegawai_id']);
        }
        if (! empty($filters['tahun'])) {
            $query->whereYear('tanggal_berangkat', $filters['tahun']);
        }
        if (! empty($filters['bulan'])) {
            $query->whereMonth('tanggal_berangkat', $filters['bulan']);
        }

        return $query->latest()->paginate(15);
    }
}
