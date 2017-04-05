<?php

namespace App\Repositories\Eloquent;

use App\Repositories\SingleKeyModelRepositoryInterface;
use Illuminate\Support\Str;

class SingleKeyModelRepository extends BaseRepository implements SingleKeyModelRepositoryInterface
{
    public function getPrimaryKey()
    {
        $model = $this->getBlankModel();

        return $model->getPrimaryKey();
    }

    public function find($id)
    {
        $modelClass = $this->getModelClassName();
        if ($this->cacheEnabled) {
            $key = $this->getCacheKey([$id]);
            \Log::info("Cache Check $key");
            $data = \Cache::remember($key, $this->cacheLifeTime, function () use ($id, $modelClass) {
                $modelClass = $this->getModelClassName();

                return $modelClass::find($id);
            });

            return $data;
        } else {
            return $modelClass::find($id);
        }
    }

    public function allByIds($ids, $order = null, $direction = null, $reorder = false)
    {
        if (count($ids) == 0) {
            return $this->getEmptyList();
        }
        $modelClass = $this->getModelClassName();
        $primaryKey = $this->getPrimaryKey();

        $query = $modelClass::whereIn($primaryKey, $ids);
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }

        $models = $query->get();

        if (!$reorder) {
            return $models;
        }

        $result = $this->getEmptyList();
        $map = [];
        foreach ($models as $model) {
            $map[ $model->id ] = $model;
        }
        foreach ($ids as $id) {
            $model = $map[ $id ];
            if (!empty($model)) {
                $result->push($model);
            }
        }

        return $result;
    }

    public function countByIds($ids)
    {
        if (count($ids) == 0) {
            return 0;
        }
        $modelClass = $this->getModelClassName();
        $primaryKey = $this->getPrimaryKey();

        return $modelClass::whereIn($primaryKey, $ids)->count();
    }

    public function getByIds($ids, $order = null, $direction = null, $offset = null, $limit = null)
    {
        if (count($ids) == 0) {
            return $this->getEmptyList();
        }
        $modelClass = $this->getModelClassName();
        $primaryKey = $this->getPrimaryKey();

        $query = $modelClass::whereIn($primaryKey, $ids);
        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }
        if (!is_null($offset) && !is_null($limit)) {
            $query = $query->offset($offset)->limit($limit);
        }

        return $query->get();
    }

    public function create($input)
    {
        $model = $this->getBlankModel();

        return $this->update($model, $input);
    }

    public function update($model, $input)
    {
        foreach ($model->getEditableColumns() as $column) {
            if (array_key_exists($column, $input)) {
                $model->$column = array_get($input, $column);
            }
        }

        if ($this->cacheEnabled) {
            $primaryKey = $this->getPrimaryKey();
            $key = $this->getCacheKey([$model->$primaryKey]);
            \Cache::forget($key);
        }

        return $this->save($model);
    }

    public function save($model)
    {
        if (!$model->save()) {
            return false;
        }

        return $model;
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'getBy')) {
            return $this->dynamicGet($method, $parameters);
        }

        if (Str::startsWith($method, 'allBy')) {
            return $this->dynamicAll($method, $parameters);
        }

        if (Str::startsWith($method, 'countBy')) {
            return $this->dynamicCount($method, $parameters);
        }

        if (Str::startsWith($method, 'findBy')) {
            return $this->dynamicFind($method, $parameters);
        }

        if (Str::startsWith($method, 'deleteBy')) {
            return $this->dynamicDelete($method, $parameters);
        }

        $className = static::class;
        throw new \BadMethodCallException("Call to undefined method {$className}::{$method}()");
    }

    private function dynamicGet($method, $parameters)
    {
        $finder = substr($method, 5);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where'.$finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        $order = array_get($parameters, 0, 'id');
        $direction = array_get($parameters, 1, 'asc');
        $offset = array_get($parameters, 2, 0);
        $limit = array_get($parameters, 3, 10);

        if (!empty($order)) {
            $direction = empty($direction) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }
        if (!is_null($offset) && !is_null($limit)) {
            $query = $query->offset($offset)->limit($limit);
        }

        return $query->get();
    }

    private function dynamicAll($method, $parameters)
    {
        $finder = substr($method, 5);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where'.$finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        $order = array_get($parameters, 0, 'id');
        $direction = array_get($parameters, 1, 'asc');

        return $query->orderBy($order, $direction)->get();
    }

    private function dynamicCount($method, $parameters)
    {
        $finder = substr($method, 7);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where'.$finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        return $query->count();
    }

    private function dynamicFind($method, $parameters)
    {
        $finder = substr($method, 6);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where'.$finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        return $query->first();
    }

    private function dynamicDelete($method, $parameters)
    {
        $finder = substr($method, 8);
        $segments = preg_split('/(And|Or)(?=[A-Z])/', $finder, -1);
        $conditionCount = count($segments);
        $conditionParams = array_splice($parameters, 0, $conditionCount);
        $model = $this->getBlankModel();
        $whereMethod = 'where'.$finder;
        $query = call_user_func_array([$model, $whereMethod], $conditionParams);

        return $query->delete();
    }
}
