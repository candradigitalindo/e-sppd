<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getByRole(string $roleId);
    public function getByEmail(string $email);
    public function assignRole(string $userId, string $roleId): bool;
    public function updatePassword(string $id, string $password): bool;
    public function toggleActive(string $id): bool;
}
