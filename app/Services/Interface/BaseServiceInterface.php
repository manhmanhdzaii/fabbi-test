<?php

namespace App\Services\Interface;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Interface BaseServiceInterface
 *
 * @package App\Services\Interface
 */
interface BaseServiceInterface
{
	/**
	 * Get all model items without pagination
	 *
	 * @param null|array $fields
	 * @param null|array $condition
	 * @param string     $sort
	 *
	 * @return Collection
	 */
	public function getAll(array $fields = null, array $condition = null, string $sort = 'id'): Collection;

	/**
	 * Find model(s) by id
	 *
	 * @param int|string $id
	 * @param null|array $fields
	 *
	 * @return mixed
	 */
	public function find(int|string $id, array $fields = null): mixed;

	/**
	 * Create new model item
	 *
	 * @param array $data
	 *
	 * @return Model
	 * @throws Exception
	 */
	public function create(array $data): Model;

	/**
	 * Update model item data by id
	 *
	 * @param int|string $id
	 * @param array      $data
	 *
	 * @return Model
	 * @throws ModelNotFoundException|Exception
	 */
	public function update(array $data, int|string $id): Model;

	/**
	 * Delete model item by id
	 *
	 * @param int|string $id
	 *
	 * @throws ModelNotFoundException|Exception
	 */
	public function delete(int|string $id): ?bool;

    /**
     * @param array $attributes
     * @return array
     */
    public function getAllAttributes(array $attributes): array;
}
