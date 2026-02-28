<?php

namespace App\Repositories\Interfaces;

interface BuktiPerjalananRepositoryInterface extends BaseRepositoryInterface
{
    public function getBySppd(string $sppdId);
    public function getByJenis(string $sppdId, string $jenis);
    public function deleteFile(string $id): bool;
}
