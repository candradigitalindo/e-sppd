<?php

namespace App\Repositories\Interfaces;

interface PengajuanUangMukaRepositoryInterface extends BaseRepositoryInterface
{
    public function getByStatus(string $status);
    public function getByPegawai(string $pegawaiId);
    public function getBySppd(string $sppdId);
    public function updateStatus(string $id, string $status, string $userId, ?string $catatan = null): bool;
    public function getPending();
}
