<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getByRole(string $roleId)
    {
        return $this->model->where('role_id', $roleId)->with('role')->get();
    }

    public function getByEmail(string $email)
    {
        return $this->model->where('email', $email)->with('role')->first();
    }

    public function assignRole(string $userId, string $roleId): bool
    {
        return (bool) $this->model->where('id', $userId)->update(['role_id' => $roleId]);
    }

    public function updatePassword(string $id, string $password): bool
    {
        return (bool) $this->model->where('id', $id)->update(['password' => Hash::make($password)]);
    }

    public function toggleActive(string $id): bool
    {
        $user             = $this->model->findOrFail($id);
        $user->is_active  = ! $user->is_active;
        return $user->save();
    }
}
