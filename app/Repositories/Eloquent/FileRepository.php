<?php namespace App\Repositories\Eloquent;

use App\Repositories\FileRepositoryInterface;
use App\Models\File;

class FileRepository extends SingleKeyModelRepository implements FileRepositoryInterface
{

    public function getBlankModel()
    {
        return new File();
    }

    public function getByFileCategoryId($fileCategoryId, $order, $direction, $offset, $limit)
    {
        $query = File::whereFileCategoryId($fileCategoryId)->whereIsEnabled(true);

        return $this->getWithQueryBuilder($query, ['id'], 'id', $order, $direction, $offset, $limit);
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

}
