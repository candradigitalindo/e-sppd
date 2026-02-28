<?php

namespace App\Repositories\Interfaces;

interface RekapPengeluaranRepositoryInterface extends BaseRepositoryInterface
{
    public function getBySpj(string $spjId);
    public function getTotalBySpj(string $spjId): array;
    public function bulkSave(string $spjId, array $items): void;
}
