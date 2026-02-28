<?php

namespace App\Repositories\Interfaces;

interface SpjRepositoryInterface extends BaseRepositoryInterface
{
    public function getBySppd(string $sppdId);
    public function getByStatus(string $status);
    public function generateNomor(): string;
    public function updateStatus(string $id, string $status): bool;
    public function getWithRekapPengeluaran(string $id);
}
