<?php

namespace App\Repositories\Contracts;

interface PhimRepositoryInterface extends BaseRepositoryInterface
{
    public function findByTitle(string $title);
    public function paginate(int $perPage = 15, array $columns = ['*']);
}
