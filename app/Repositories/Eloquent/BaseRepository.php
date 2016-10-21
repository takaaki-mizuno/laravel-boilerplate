<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Base;

class BaseRepository implements BaseRepositoryInterface
{
    protected $cacheEnabled = false;

    protected $cachePrefix = 'model';

    protected $cacheLifeTime = 60; // Minutes

    public function getEmptyList()
    {
        return new Collection();
    }

    public function getModelClassName()
    {
        $model = $this->getBlankModel();

        return get_class($model);
    }

    public function getBlankModel()
    {
        return new Base();
    }

    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }

    public function validator(array $data)
    {
        return \Validator::make($data, $this->rule());
    }

    public function all($order = null, $direction = null)
    {
        $model = $this->getModelClassName();
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;

            return $model::orderBy($order, $direction)->get();
        }

        return $model::all();
    }

    public function allEnabled($order = null, $direction = null)
    {
        $model = $this->getModelClassName();
        $query = $model::where('is_enabled', '=', true);
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }

        return $query->get();
    }

    public function get($order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        $model = $this->getModelClassName();

        return $model::orderBy($order, $direction)->skip($offset)->take($limit)->get();
    }

    public function getEnabled($order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        $model = $this->getModelClassName();

        return $model::where('is_enabled', '=', true)->orderBy($order, $direction)->skip($offset)->take($limit)->get();
    }

    public function count()
    {
        $model = $this->getModelClassName();

        return $model::count();
    }

    public function countEnabled()
    {
        $model = $this->getModelClassName();

        return $model::where('is_enabled', '=', true)->count();
    }

    public function getAPIArray($models)
    {
        $ret = [];
        foreach ($models as $model) {
            $ret[] = $model->toAPIArray();
        }

        return $ret;
    }

    public function pluck($collection, $value, $key = null)
    {
        $items = [];
        foreach ($collection as $model) {
            if (empty($key)) {
                $items[] = $model->$value;
            } else {
                $items[ $model->$key ] = $model->$value;
            }
        }

        return Collection::make($items);
    }

    /**
     * @param integer[] $ids
     *
     * @return string
     */
    protected function getCacheKey($ids)
    {
        $key = $this->cachePrefix;
        foreach ($ids as $id) {
            $key .= '-'.$id;
        }

        return $key;
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param string[]                           $orderCandidates
     * @param string                             $orderDefault
     * @param string                             $order
     * @param string                             $direction
     * @param int                                $offset
     * @param int                                $limit
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getWithQueryBuilder(
        $query,
        $orderCandidates = [],
        $orderDefault = 'id',
        $order,
        $direction,
        $offset,
        $limit
    ) {
        $order = strtolower($order);
        $direction = strtolower($direction);
        $offset = intval($offset);
        $limit = intval($limit);
        $order = in_array($order, $orderCandidates) ? $order : strtolower($orderDefault);
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        if ($limit <= 0) {
            $limit = 10;
        }
        if ($offset < 0) {
            $offset = 0;
        }

        return $query->orderBy($order, $direction)->offset($offset)->limit($limit)->get();
    }
}
