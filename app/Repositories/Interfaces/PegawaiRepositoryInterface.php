<?php

namespace App\Repositories\Interfaces;

interface PegawaiRepositoryInterface extends BaseRepositoryInterface
{
    public function getByUnitKerja(string $unitKerjaId);
    public function getByJabatan(string $jabatanId);
    public function getByGolongan(string $golonganId);
    public function getActiveWithRelations();
}
