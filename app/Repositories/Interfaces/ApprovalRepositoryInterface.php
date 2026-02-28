<?php

namespace App\Repositories\Interfaces;

interface ApprovalRepositoryInterface extends BaseRepositoryInterface
{
    public function getPendingByUser(string $userId);
    public function approve(string $id, string $userId, ?string $catatan = null): bool;
    public function reject(string $id, string $userId, string $catatan): bool;
    public function getByApprovable(string $type, string $id);
    public function getHistoryByUser(string $userId);
}
