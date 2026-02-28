<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    public function allWithPaginate(int $perPage = 15, array $columns = ['*'], array $relations = []): LengthAwarePaginator
    {
        return $this->model->with($relations)->latest()->paginate($perPage, $columns);
    }

    public function findById(string $id, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->with($relations)->find($id, $columns);
    }

    public function findByField(string $field, mixed $value, array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->where($field, $value)->get($columns);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): bool
    {
        $record = $this->findById($id);
        if (! $record) {
            return false;
        }

        return $record->update($data);
    }

    public function delete(string $id): bool
    {
        $record = $this->findById($id);
        if (! $record) {
            return false;
        }

        return $record->delete();
    }

    public function search(string $keyword, array $fields = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (! empty($fields) && ! empty($keyword)) {
            $query->where(function (Builder $q) use ($keyword, $fields) {
                foreach ($fields as $field) {
                    $q->orWhere($field, 'ilike', "%{$keyword}%");
                }
            });
        }

        return $query->latest()->paginate($perPage);
    }
}
