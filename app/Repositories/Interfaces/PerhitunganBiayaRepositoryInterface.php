<?php

namespace App\Repositories\Interfaces;

interface PerhitunganBiayaRepositoryInterface extends BaseRepositoryInterface
{
    public function getBySppd(string $sppdId);
    public function getByJenis(string $sppdId, string $jenis);
    public function getTotalBySppd(string $sppdId): float;
    public function hitungOtomatis(string $sppdId): array;
    public function bulkSave(string $sppdId, array $items): void;
}
