<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = []);
    public function allWithPaginate(int $perPage = 15, array $columns = ['*'], array $relations = []);
    public function findById(string $id, array $columns = ['*'], array $relations = []);
    public function findByField(string $field, mixed $value, array $columns = ['*'], array $relations = []);
    public function create(array $data);
    public function update(string $id, array $data): bool;
    public function delete(string $id): bool;
    public function search(string $keyword, array $fields = [], int $perPage = 15);
}
