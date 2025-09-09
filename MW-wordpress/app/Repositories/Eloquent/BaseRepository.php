<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll(array $columns = ['*'])
    {
        return $this->model->newQuery()->get($columns);
    }

    public function find($id)
    {
        return $this->model->newQuery()->find($id);
    }

    public function create(array $data)
    {
        return $this->model->newQuery()->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        if (! $record) {
            return null;
        }
        $record->fill($data);
        $record->save();
        return $record;
    }

    public function delete($id)
    {
        $record = $this->find($id);
        if (! $record) {
            return false;
        }
        return $record->delete();
    }
}
