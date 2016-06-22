<?php namespace App\Repositories\Eloquent;

use App\Repositories\SingleKeyModelRepositoryInterface;

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
        if (!empty( $order )) {
            $direction = empty( $direction ) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }

        $models = $query->get();

        if( !$reorder ) {
            return $models;
        }

        $result = $this->getEmptyList();
        $map = [];
        foreach( $models as $model ) {
            $map[$model->id] = $model;
        }
        foreach( $ids as $id ) {
            $model = $map[$id];
            if( !empty($model) ) {
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
        if (!empty( $order )) {
            $direction = empty( $direction ) ? 'asc' : $direction;
            $query = $query->orderBy($order, $direction);
        }
        if (!empty( $offset ) && !empty( $limit )) {
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
            \Log::info("Cache Remove $key");
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

}
