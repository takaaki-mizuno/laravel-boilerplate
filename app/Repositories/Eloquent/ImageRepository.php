<?php namespace App\Repositories\Eloquent;

use App\Repositories\ImageRepositoryInterface;
use App\Models\Image;

class ImageRepository extends SingleKeyModelRepository implements ImageRepositoryInterface
{

    public function getBlankModel()
    {
        return new Image();
    }

    public function getByFileCategory($fileCategory, $order, $direction, $offset, $limit)
    {
        $query = Image::whereFileCategory($fileCategory)->whereIsEnabled(true);

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

}
