<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function getWithPermissions()
    {
        return $this->model->with('permissions')
            ->withCount(['permissions', 'users'])
            ->get();
    }

    public function syncPermissions(string $roleId, array $permissionIds): void
    {
        $role = $this->model->findOrFail($roleId);
        $role->permissions()->sync($permissionIds);
    }

    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)->with('permissions')->first();
    }

    public function getForDropdown()
    {
        return $this->model->orderBy('name')->get(['id', 'name', 'slug']);
    }
}
