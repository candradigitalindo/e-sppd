<?php

namespace App\Repositories\Interfaces;

interface DetailPerjalananRepositoryInterface extends BaseRepositoryInterface
{
    public function getBySppd(string $sppdId);
    public function bulkCreate(string $sppdId, array $details): void;
}
