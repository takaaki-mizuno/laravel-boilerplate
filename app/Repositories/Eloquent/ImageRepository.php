<?php

namespace App\Repositories\Eloquent;

use App\Repositories\ImageRepositoryInterface;
use App\Models\Image;

class ImageRepository extends SingleKeyModelRepository implements ImageRepositoryInterface
{
    public function getBlankModel()
    {
        return new Image();
    }

    public function getByFileCategoryType($fileCategory, $order, $direction, $offset, $limit)
    {
        $query = Image::whereFileCategoryType($fileCategory)->whereIsEnabled(true);

        return $this->getWithQueryBuilder($query, ['id'], 'id', $order, $direction, $offset, $limit);
    }

    public function findByUrl($url)
    {
        return Image::whereUrl($url)->whereIsEnabled(true)->first();
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

    public function allByEntityTypeAndEntityId($type, $entityId)
    {
        return Image::whereEntityType($type)->whereEntityId($entityId)->get();
    }
}
