<?php

namespace App\Repositories\Interfaces;

interface SppdRepositoryInterface extends BaseRepositoryInterface
{
    public function getByPegawai(string $pegawaiId);
    public function getByStatus(string $status);
    public function getBySuratTugas(string $suratTugasId);
    public function generateNomor(): string;
    public function updateStatus(string $id, string $status): bool;
    public function getWithRelations(string $id);
    public function getForMonitoring(array $filters = []);
}
