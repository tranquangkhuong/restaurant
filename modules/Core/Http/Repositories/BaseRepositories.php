<?php

namespace Modules\Core\Http\Repositories;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Modules\Core\Exceptions\ResponseException;
use Illuminate\Support\Str;

/**
 * Base repository
 *
 * created at 05/08/2022
 * @author khuongtq
 */
abstract class BaseRepository
{
    private $app;
    protected $model;

    public function __construct()
    {
        $this->app = new App();
        $this->makeModel();
    }

    abstract public function model();

    public function makeModel()
    {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Get list data model with filter
     *
     * @param mixed $data
     * @param array $relations
     * @param array $relationCounts
     *
     * @return Collection $entities
     */
    public function filter($data, $relations = [], $relationCounts = [])
    {
        $data = collect($data);

        // select list column
        $entities = $this->model->select($this->model->selectable ?? ['*']);

        $currentPage = ($data->has('offset') && (int) $data['offset'] > 1)
            ? (int) $data['offset']
            : 1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        // load relations
        if (count($relations) > 0) {
            $entities = $entities->with($relations);
        }

        // load relation counts
        if (count($relationCounts) > 0) {
            $entities = $entities->withCount($relationCounts);
        }

        // filter data
        if (count($data) > 1 && method_exists($this->model, 'filter')) {
            foreach ($data as $key => $value) {
                $entities = $this->model->filter($entities, $key, $value);
            }
        }

        // order
        $order = ($data->has('sort') && in_array($data['sort'], $this->model->sortable))
            ? $data['sort']
            : '';
        if ($order !== '') {
            $sortType = ($data->has('sortType') && $data['sortType'] != '')
                ? $data['sortType']
                : 'desc';
            $entities = $entities->orderBy($order, $sortType);
        }

        // limit
        $limit = ($data->has('limit') && $data['limit'] != '') ? (int) $data['limit'] : 50;

        return $entities->paginate($limit);
    }

    /**
     * Create model.
     *
     * @param array $data
     *
     * @return Model
     */
    public function create($data = [])
    {
        if (!$this->model->getIncrementing()) {
            $id = $this->model->getKeyName();
            if ($id) {
                $data[$id] = Str::uuid()->toString();
            }
        }
        if (!isset($data['created_at'])) {
            $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
        }
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
        }
        return $this->model->create($data);
    }

    /**
     * Update model.
     *
     * @param Model $entity
     * @param array $data
     *
     * @return Model
     */
    public function update($entity, $data = [])
    {
        if (!$entity || !$entity instanceof Model) {
            throw new ResponseException("Không tồn tại đối tượng!");
        }
        $data['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
        $entity->update($data);
        return $entity;
    }

    /**
     * Update or create model.
     *
     * @param array $condition
     * @param array $data
     *
     * @return Model
     */
    public function updateOrCreate($condition = [], $data = [])
    {
        return $this->model->updateOrCreate($condition, $data);
    }

    /**
     * Delete model.
     *
     * @param Model $entity
     *
     * @return void
     */
    public function delete($entity)
    {
        if (!$entity || !$entity instanceof Model) {
            throw new ResponseException("Không tồn tại đối tượng!");
        }
        $entity->delete();
        return $entity;
    }

    /**
     * Delete mutiple item.
     *
     * @param array $ids
     *
     * @return void
     */
    public function deleteMulti(array $ids = [])
    {
        return $this->model->whereIn('id', $ids)->delete();
    }


    /**
     * Get model count.
     *
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Get model total.
     *
     * @return int
     */
    public function total($field)
    {
        return $this->model->sum($field);
    }

    /**
     * Insert multiple values.
     *
     * @return int
     */
    public function insert($data)
    {
        return $this->model->insert($data);
    }

    /**
     * Group model by column.
     *
     * @param string $field
     *
     * @return void
     */
    public function groupBy($field)
    {
        $raw = $field . ', count(' . $field . ') as ' . $field . '_count';
        return $this->model->select(DB::raw($raw))->groupBy($field)->get();
    }

    /**
     * Find model by id.
     *
     * @param mixed $id
     * @param array $relations
     *
     * @return Model
     */
    public function findOrFail($id, $relations = [])
    {
        $entity = $this->model->findOrFail($id);
        if (count($relations)) {
            return $entity->load($relations);
        }

        return $entity;
    }

    /**
     * Find model by id.
     *
     * @param mixed $id
     * @param array $relations
     *
     * @return Model
     */
    public function find($id, $relations = [])
    {
        $entity = $this->model->find($id);
        if (count($relations)) {
            return $entity->load($relations);
        }

        return $entity;
    }

    /**
     * Find by condition .
     *
     * @param mixed $request
     * @param array $relations
     *
     * @return object $entities
     */
    public function findWhere($condition, $relations = [])
    {
        $entities = $this->model->select($this->model->selectable ?? ['*']);
        if (count($relations)) {
            $entities = $entities->with($relations);
        }
        if (count($condition)) {
            foreach ($condition as $key => $value) {
                $entities = $entities->where($key, $value);
            }
        }

        return $entities;
    }

    /**
     * where by condition .
     *
     * @param mixed $column
     *
     * @return object $entities
     */
    public function where(...$column)
    {
        if (count($column) == 3) {
            return $this->model->where($column[0], $column[1], $column[2]);
        } else {
            return $this->model->where($column[0], $column[1]);
        }
    }

    /**
     * where column in array values
     *
     * @param mixed $column
     *
     * @return object $entities
     */
    public function whereIn($column, $values = [])
    {
        return $this->model->whereIn($column, $values);
    }

    /**
     * where by condition .
     *
     * @param mixed $column
     *
     * @return object $entities
     */
    public function select(...$column)
    {
        return $this->model->select(...$column);
    }

    /**
     * Get all data.
     *
     * @return  List of Model
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get model's fillable attribute.
     *
     * @return array
     */
    public function getFillable()
    {
        return $this->model->getFillable();
    }
}
