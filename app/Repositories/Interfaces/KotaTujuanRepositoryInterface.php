<?php

namespace App\Repositories\Interfaces;

interface KotaTujuanRepositoryInterface extends BaseRepositoryInterface
{
    public function getByProvinsi(string $provinsi);
    public function getByKategori(string $kategori);
    public function getForDropdown();
}
