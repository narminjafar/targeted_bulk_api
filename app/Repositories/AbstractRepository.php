<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    protected Model $model;

    /**
     * @param int $id
     * @return Model|null
     */

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $filters = [])
    {
        $query = $this->model->query();
        foreach ($filters as $field => $value) {
            $query->where($field, $value);
        }
        return $query->get();
    }

    public function paginate(int $perPage = 10, array $filters = [])
    {
        $query = $this->model->query();
        foreach ($filters as $field => $value) {
            $query->where($field, $value);
        }
        return $query->paginate($perPage);
    }

    public function find(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update($model, array $data): Model
    {
        $model->update($data);
        return $model;
    }

    public function delete($model)
    {
        return $model->delete();
    }

}
