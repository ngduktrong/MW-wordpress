<?php

namespace App\CRUD;

use App\Repositories\Contracts\BaseRepositoryInterface;

/**
 * BaseCRUD: lớp CRUD chung cho mọi entity.
 *
 * @property BaseRepositoryInterface $repo
 */
class BaseCRUD
{
    /**
     * Repository chung (gán trong constructor).
     *
     * Sử dụng phpdoc thay vì typed property để tương thích PHP < 7.4.
     *
     * @var \App\Repositories\Contracts\BaseRepositoryInterface
     */
    protected $repo;

    /**
     * BaseCRUD constructor.
     *
     * @param BaseRepositoryInterface $repo
     */
    public function __construct(BaseRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function all(array $columns = ['*'])
    {
        return $this->repo->getAll($columns);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        // Nơi đặt validate/normalize chung nếu cần
        return $this->repo->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}
