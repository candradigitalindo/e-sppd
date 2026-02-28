<?php

namespace App\Repositories\Eloquent;

use App\Models\BuktiPerjalanan;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Interfaces\BuktiPerjalananRepositoryInterface;

class BuktiPerjalananRepository extends BaseRepository implements BuktiPerjalananRepositoryInterface
{
    public function __construct(BuktiPerjalanan $model)
    {
        parent::__construct($model);
    }

    public function getBySppd(string $sppdId)
    {
        return $this->model->where('sppd_id', $sppdId)->get();
    }

    public function getByJenis(string $sppdId, string $jenis)
    {
        return $this->model->where('sppd_id', $sppdId)->where('jenis', $jenis)->get();
    }

    public function deleteFile(string $id): bool
    {
        $bukti = $this->model->find($id);
        if (! $bukti) {
            return false;
        }

        if ($bukti->file_path && Storage::exists($bukti->file_path)) {
            Storage::delete($bukti->file_path);
        }

        return $bukti->delete();
    }
}
