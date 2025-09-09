<?php

namespace App\CRUD;

use App\Repositories\Contracts\PhimRepositoryInterface;

/**
 * PhimCRUD kế thừa BaseCRUD.
 *
 * Không khai lại property $repo (tồn tại ở BaseCRUD).
 */
class PhimCRUD extends BaseCRUD
{
    /**
     * Constructor nhận PhimRepositoryInterface
     *
     * @param PhimRepositoryInterface $repo
     */
    public function __construct(PhimRepositoryInterface $repo)
    {
        // parent sẽ gán $this->repo (kiểu BaseRepositoryInterface ở mức parent)
        parent::__construct($repo);
    }

    /**
     * Tìm phim theo tiêu đề (sử dụng repository).
     *
     * @param string $q
     * @return mixed
     */
    public function searchByTitle(string $q)
    {
        // Gán local var và annotate để IDE biết đây là PhimRepositoryInterface
        /** @var PhimRepositoryInterface $repo */
        $repo = $this->repo;

        // Runtime safety: đảm bảo object thực sự implement interface
        if (! $repo instanceof PhimRepositoryInterface) {
            throw new \RuntimeException('Repository injected to PhimCRUD must implement PhimRepositoryInterface.');
        }

        return $repo->findByTitle($q);
    }

    /**
     * Phân trang (dùng method đặc thù của PhimRepository).
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate(int $perPage = 10)
    {
        /** @var PhimRepositoryInterface $repo */
        $repo = $this->repo;

        if (! $repo instanceof PhimRepositoryInterface) {
            throw new \RuntimeException('Repository injected to PhimCRUD must implement PhimRepositoryInterface.');
        }

        return $repo->paginate($perPage);
    }
}
