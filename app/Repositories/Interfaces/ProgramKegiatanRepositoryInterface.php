<?php

namespace App\Repositories\Interfaces;

interface ProgramKegiatanRepositoryInterface extends BaseRepositoryInterface
{
    public function getByTahun(int $tahun);
    public function getForDropdown(int $tahun);
    public function getTotalPagu(int $tahun): float;
}
