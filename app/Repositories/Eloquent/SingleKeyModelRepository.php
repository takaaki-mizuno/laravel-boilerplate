<?php namespace App\Repositories\Eloquent;

use App\Repositories\SingleKeyModelRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Base;

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
        if (!empty($offset) && !empty($limit)) {
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
        return $model->save();
    }

    public function delete($model)
    {
        return $model->delete();
    }

}