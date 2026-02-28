<?php

namespace App\Repositories\Interfaces;

interface SuratTugasRepositoryInterface extends BaseRepositoryInterface
{
    public function getWithPegawai();
    public function getByStatus(string $status);
    public function generateNomor(): string;
    public function tambahPegawai(string $suratTugasId, array $pegawaiIds): void;
    public function hapusPegawai(string $suratTugasId, string $pegawaiId): void;
    public function updateStatus(string $id, string $status): bool;
}
