<?php

namespace App\Repositories\Interfaces;

interface AuditLogRepositoryInterface extends BaseRepositoryInterface
{
    public function getByUser(string $userId);
    public function getByModule(string $module);
    public function log(string $action, string $module, ?array $dataBefore = null, ?array $dataAfter = null): void;
    public function cleanup(int $daysOld = 90): int;
}
