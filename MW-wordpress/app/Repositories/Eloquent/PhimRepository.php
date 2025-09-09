<?php

namespace App\Repositories\Eloquent;

use App\Models\Phim;
use App\Repositories\Contracts\PhimRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PhimRepository extends BaseRepository implements PhimRepositoryInterface
{
    public function __construct(Phim $model)
    {
        parent::__construct($model);
    }

    public function findByTitle(string $title)
    {
        return $this->model->newQuery()
            ->where('TenPhim', 'LIKE', "%{$title}%")
            ->get();
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->newQuery()->paginate($perPage, $columns);
    }
}
