<?php

namespace App\Repositories\Interfaces;

interface RencanaPerjalananRepositoryInterface extends BaseRepositoryInterface
{
    public function getByPegawai(string $pegawaiId);
    public function getByStatus(string $status);
    public function getPending();
    public function updateStatus(string $id, string $status, ?string $catatan = null): bool;
}
