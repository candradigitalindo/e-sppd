<?php

namespace App\Repositories\Interfaces;

interface LaporanPerjalananRepositoryInterface extends BaseRepositoryInterface
{
    public function getBySppd(string $sppdId);
    public function getByPegawai(string $pegawaiId);
    public function getDashboardStats(int $tahun): array;
}
