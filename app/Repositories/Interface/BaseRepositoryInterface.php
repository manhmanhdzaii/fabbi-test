<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface BaseRepositoryInterface
 *
 * @package App\Repositories\Interface
 */
interface BaseRepositoryInterface
{
	public function getAll(array $fields = null, array $condition = null, string $sort = 'id');

	public function create(array $data);

	public function insert(array $data);

	public function update(array $data, $id): Model;

	public function updateOrCreate(array $data, array $condition = []);

	public function updateCondition(array $data, array $condition = []);

	public function bulkUpdate(array $values, array $attributes, $key = 'id');

	public function destroy(int $id);

	public function find(int $id, array $fields = null);

	public function findOrBad(int $id);

	public function filter(array $condition);

	public function paginated($limit = 10, $condition = null, $sort = null, $direction = null);

	public function getAllAttributes(array $attributes): array;
}

