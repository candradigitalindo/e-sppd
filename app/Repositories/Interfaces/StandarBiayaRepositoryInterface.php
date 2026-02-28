<?php

namespace App\Repositories\Interfaces;

interface StandarBiayaRepositoryInterface extends BaseRepositoryInterface
{
    public function getByGolonganAndKota(string $golonganId, string $kotaId, int $tahun);
    public function getByTahun(int $tahun);
    public function getForCalculation(string $golonganId, string $kotaId, int $tahun);
}
