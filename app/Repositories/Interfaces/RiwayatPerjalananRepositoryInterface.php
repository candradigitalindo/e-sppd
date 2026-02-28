<?php

namespace App\Repositories\Interfaces;

interface RiwayatPerjalananRepositoryInterface extends BaseRepositoryInterface
{
    public function getBySppd(string $sppdId);
    public function addRiwayat(string $sppdId, string $status, string $keterangan): void;
}
