<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function getAll(array $columns = ['*']);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
