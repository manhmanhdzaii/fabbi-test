<?php

namespace App\Repositories;

use App\Repositories\Interface\BaseRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class BaseRepository
 *
 * @package App\Repositories
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
	/** @var array $relations */
	public static array $relations = [];
	/**
	 * model property on class instances
	 */
	public Model $model;

	/**
	 * Constructor to bind model to repo
	 *
	 * @param Model $model
	 */
	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	/**
	 * Get the associated model
	 */
	public function getModel(): Model
	{
		return $this->model;
	}

	/**
	 * Set the associated model
	 *
	 * @param $model
	 *
	 * @return BaseRepository
	 */
	public function setModel($model): BaseRepository
	{
		$this->model = $model;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getGuarded(): array
	{
		return $this->model->getGuarded();
	}

	/**
	 * Get all instances of model
	 *
	 * @param null|array $fields
	 * @param array|null $condition
	 * @param string     $sort
	 *
	 * @return Collection
	 */
	public function getAll(array $fields = null, array $condition = null, string $sort = 'id'): Collection
	{
		if (!empty($condition)) {
			return $this->model->when(
				!empty($condition),
				function ($q) use ($condition) {
					return $q->where($condition);
				}
			)
				->orderByDesc($sort)->get();
		}
		return $this->model->all($fields ?? ['*'])->sortByDesc($sort);
	}

	/**
	 * @param $ids
	 *
	 * @return mixed
	 */
	public function getMany($ids): mixed
	{
		return $this->model->whereIn('id', $ids)->get();
	}

	/**
	 * Count all instances of model
	 *
	 * @param array|null $condition
	 *
	 * @return mixed
	 */
	public function countAll(array $condition = null): mixed
	{
		return $this->model->when(
			!empty($condition),
			function ($q) use ($condition) {
				return $q->where($condition);
			}
		)->count();
	}

	/**
	 * create a new record in the database
	 *
	 * @param array $data
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function create(array $data): mixed
	{
		return tap(
			$this->model->create($data),
			function ($instance) {
				if (!$instance) {
					throw new Exception(__('exceptions.actions.create_failed'));
				}
			}
		)->fresh(static::$relations);
	}

	/**
	 * create a new record in the database
	 *
	 * @param array $data
	 *
	 * @return mixed
	 */
	public function insert(array $data): mixed
	{
		$data['created_at'] = Carbon::now();
		return $this->model->insert($data);
	}

	/**
	 * @param $attributes
	 *
	 * @return bool
	 */
	public function insertMany($attributes): bool
	{
		return DB::table($this->model->getTable())->insert($attributes);
	}

	/**
	 * update record in the database
	 *
	 * @param array $data
	 * @param null  $id
	 *
	 * @return Model
	 * @throws \Exception
	 */
	public function update(array $data, $id = null): Model
	{
		$model = tap(
			$this->find($id),
			function ($instance) use ($data) {
				if (!$instance->update($data)) {
					throw new Exception(__('exceptions.actions.update_failed'));
				}
			}
		);
		return $model->load(static::$relations);
	}

	/**
	 * show the record with the given id
	 *
	 * @param            $id
	 * @param array|null $fields
	 *
	 * @return mixed
	 */
	public function find($id, array $fields = null): mixed
	{
		if (!empty($fields)) {
			return $this->model->find($id, $fields);
		}
		return $this->model->find($id);
	}

	/**
	 * update or create a new record in the database
	 *
	 * @param array $data
	 * @param array $condition
	 *
	 * @return mixed
	 */
	public function updateOrCreate(array $data, array $condition = []): mixed
	{
		return $this->model->updateOrCreate($condition, $data);
	}

	/**
	 * update record in the database
	 *
	 * @param array $data
	 * @param array $condition
	 *
	 * @return mixed
	 */
	public function updateCondition(array $data, array $condition = []): mixed
	{
		return $this->model->where($condition)->update($data);
	}

	/**
	 * @param array  $values
	 * @param array  $attributes
	 * @param string $key
	 *
	 * @return bool
	 */
	public function bulkUpdate(array $values, array $attributes, $key = 'id'): bool
	{
		if (!empty($attributes)) {
			return $this->model->whereIn($key, $values)
				->update($attributes);
		}
		return false;
	}

	/**
	 * update record in the database
	 *
	 * @param array $condition
	 * @param       $column
	 * @param       $num
	 *
	 * @return mixed
	 */
	public function increment(array $condition, $column, $num): mixed
	{
		return $this->model->where($condition)->increment($column, $num);
	}

	/**
	 * update record in the database
	 *
	 * @param array $condition
	 * @param       $column
	 * @param       $num
	 *
	 * @return mixed
	 */
	public function decrement(array $condition, $column, $num): mixed
	{
		return $this->model->where($condition)->decrement($column, $num);
	}

	/**
	 * remove record from the database
	 *
	 * @param int $id
	 *
	 * @return bool|null
	 * @throws Exception
	 */
	public function destroy(int $id): ?bool
	{
		return $this->model->destroy($id);
	}

	/**
	 * Check if model exists
	 *
	 * @param int $id
	 *
	 * @return mixed
	 */
	public function exists(int $id): mixed
	{
		return $this->model->exists($id);
	}

	/**
	 * Get one
	 *
	 * @param int $id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function findOrBad(int $id): mixed
	{
		try {
			$result = $this->model->findOrFail($id);
		} catch (Exception $exception) {
			throw new Exception($exception->getMessage());
		}
		return $result;
	}

	/**
	 * @param array $condition
	 *
	 * @return mixed
	 */
	public function filter(array $condition): mixed
	{
		return $this->model->where($condition)->get();
	}

	/**
	 * Eager load database relationships
	 *
	 * @param array $relations
	 *
	 * @return Builder
	 */
	public function with(array $relations): Builder
	{
		return $this->model->with($relations);
	}

	/**
	 * The pluck method retrieves all of the values for a given key
	 *
	 * @param      $value
	 * @param      $key
	 * @param null $condition
	 *
	 * @return mixed
	 */
	public function pluck($value, $key, $condition = null): mixed
	{
		return $this->model
			->when(
				!empty($condition),
				function ($q) use ($condition) {
					return $q->where($condition);
				}
			)
			->pluck($value, $key);
	}

	/**
	 *  Get all instances of model with paginate
	 *
	 * @param int  $limit
	 * @param null $condition
	 * @param null $sort
	 * @param null $direction
	 *
	 * @return mixed
	 */
	public function paginated($limit = 10, $condition = null, $sort = null, $direction = null): mixed
	{
		return $this->model
			->when(
				!empty($condition),
				function ($q) use ($condition) {
					return $q->where($condition);
				}
			)
			->when(
				!empty($sort),
				function ($query) use ($sort, $direction) {
					return $query->orderBy($sort, $direction);
				}
			) // sort
			->paginate($limit);
	}

	/**
	 * @param array $attributes
	 *
	 * @return array
	 */
	public function getAllAttributes(array $attributes): array
	{
		$columns = [];
		foreach ($attributes as $key => $value) {
			if (in_array($key, $this->getFillable())) {
				$columns[$key] = $value;
			}
		}
		return $columns;
	}

	/**
	 * @return array
	 */
	public function getFillable(): array
	{
		return $this->model->getFillable();
	}
}
