<?php

namespace App\Repositories\Eloquent;

use App\Models\SuratTugas;
use App\Repositories\Interfaces\SuratTugasRepositoryInterface;

class SuratTugasRepository extends BaseRepository implements SuratTugasRepositoryInterface
{
    public function __construct(SuratTugas $model)
    {
        parent::__construct($model);
    }

    public function getWithPegawai()
    {
        return $this->model->with(['pegawais', 'dibuatOleh', 'programKegiatan'])
            ->latest()
            ->paginate(15);
    }

    public function getByStatus(string $status)
    {
        return $this->model->where('status', $status)
            ->with(['pegawais'])
            ->latest()
            ->paginate(15);
    }

    public function generateNomor(): string
    {
        $tahun = date('Y');
        $bulan = date('m');
        $count = $this->model->whereYear('created_at', $tahun)->count() + 1;
        return sprintf('SPT/%s/%s/%04d', $bulan, $tahun, $count);
    }

    public function tambahPegawai(string $suratTugasId, array $pegawaiIds): void
    {
        $spt = $this->model->findOrFail($suratTugasId);
        $spt->pegawais()->syncWithoutDetaching($pegawaiIds);
    }

    public function hapusPegawai(string $suratTugasId, string $pegawaiId): void
    {
        $spt = $this->model->findOrFail($suratTugasId);
        $spt->pegawais()->detach($pegawaiId);
    }

    public function updateStatus(string $id, string $status): bool
    {
        return (bool) $this->model->where('id', $id)->update(['status' => $status]);
    }
}
