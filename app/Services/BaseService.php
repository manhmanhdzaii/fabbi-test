<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use App\Services\Interface\BaseServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class BaseService
 *
 * @package App\Services
 */
class BaseService implements BaseServiceInterface
{
    public BaseRepository $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all instances of model
     *
     * @param null|array $fields
     * @param array|null $condition
     * @param string $sort
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(array $fields = null, array $condition = null, string $sort = 'id'): Collection
    {
        return $this->repository->getAll($fields, $condition, $sort);
    }

    /**
     * @param int|string $id
     * @param null|array $fields
     *
     * @return mixed
     */
    public function find(int|string $id, array $fields = null): mixed
    {
        return $this->repository->find($id, $fields);
    }

    /**
     * Create new model item
     *
     * @param array $data
     *
     * @return Model
     *
     * @throws Exception
     */
    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    /**
     * Update model item data by id
     *
     * @param int|string $id
     * @param array $data
     *
     * @return Model
     * @throws ModelNotFoundException|Exception
     */
    public function update(array $data, int|string $id): Model
    {
        return $this->repository->update($data, $id);
    }

    public function createOrUpdate(array $attr, array $data): Model
    {
        return $this->repository->updateOrCreate($attr, $data);
    }

    /**
     * remove record from the database
     * @param int|string $id
     * @return bool|null
     * @throws Exception
     */
    public function delete(int|string $id): ?bool
    {
        return $this->repository->destroy($id);
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function getAllAttributes(array $attributes): array
    {
        return $this->repository->getAllAttributes($attributes);
    }
}
