<?php

namespace App\Repositories\Interfaces;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function getWithPermissions();
    public function syncPermissions(string $roleId, array $permissionIds): void;
    public function findBySlug(string $slug);
    public function getForDropdown();
}
