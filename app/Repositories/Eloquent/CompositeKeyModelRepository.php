<?php namespace App\Repositories\Eloquent;

use App\Repositories\CompositeKeyModelRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CompositeKeyModelRepository extends BaseRepository implements CompositeKeyModelRepositoryInterface
{

    public function getPrimaryKeys()
    {
        $model = $this->getBlankModel();
        $primaryKeys = $model->getPrimaryKey();

        return is_array($primaryKeys) ? $primaryKeys : [$primaryKeys];
    }

    public function find(array $conditions)
    {
        $primaryKeys = $this->getPrimaryKeys();
        if (!is_array($primaryKeys) || count($primaryKeys) == 0) {
            return null;
        }
        $modelClass = $this->getModelClassName();
        /** @var \Illuminate\Database\Eloquent\Collection $query */
        $query = $modelClass::where($primaryKeys[0], '=', array_get($conditions, $primaryKeys[0]));
        if (count($primaryKeys) > 1) {
            for ($i = 1; $i < count($primaryKeys); $i++) {
                $query->where($primaryKeys[ $i ], '=', array_get($conditions, $primaryKeys[ $i ]));
            }
        }

        return $query->first();
    }

    public function create($input)
    {
        $primaryKeys = $this->getPrimaryKeys();
        $existingModel = $this->find($input);
        if (!empty($existingModel)) {
            return $this->update($existingModel, $input);
        }

        foreach ($primaryKeys as $primaryKey) {
            if (!array_key_exists($primaryKey, $input)) {
                return null;
            }
        }

        $modelClass = $this->getModelClassName();
        $now = \DateTimeHelper::now();

        if (!array_key_exists('created_at', $input)) {
            $input['created_at'] = $now;
        }
        $input['updated_at'] = $now;
        \DB::table($modelClass::getTableName())->insert($input);

        return $this->find($input);
    }

    public function update($model, $input)
    {
        $primaryKeys = $this->getPrimaryKeys();
        foreach ($primaryKeys as $key) {
            $input[ $key ] = $model->$key;
        }
        $input['created_at'] = $model->created_at;
        $this->delete($model);

        return $this->create($input);
    }

    public function delete($model)
    {
        $primaryKeys = $this->getPrimaryKeys();
        if (!is_array($primaryKeys) || count($primaryKeys) == 0) {
            return null;
        }
        $modelClass = $this->getModelClassName();
        /** @var \Illuminate\Database\Eloquent\Collection $query */
        $query = $modelClass::where($primaryKeys[0], '=', $model->$primaryKeys[0]);
        if (count($primaryKeys) > 1) {
            for ($i = 1; $i < count($primaryKeys); $i++) {
                $query->where($primaryKeys[ $i ], '=', $model->$primaryKeys[ $i ]);
            }
        }
        $query->delete();

        return true;
    }

}
