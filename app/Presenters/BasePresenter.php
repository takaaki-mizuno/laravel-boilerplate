<?php

namespace App\Presenters;

use App\Models\Image;
use App\Repositories\ImageRepositoryInterface;

class BasePresenter
{
    /**
     * @var \App\Models\Base
     */
    protected $entity;

    /**
     * @var string[]
     */
    protected $multilingualFields = [];

    /**
     * @var string[]
     */
    protected $imageFields = [];

    /**
     * @param \App\Models\Base $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if (method_exists($this, $property)) {
            return $this->$property();
        }

        if (in_array($property, $this->multilingualFields)) {
            return $this->entity->getLocalizedColumn($property);
        }

        if (in_array($property, $this->entity->getDateColumns())) {
            return \DateTimeHelper::changeToPresentationTimeZone($this->entity->$property);
        }

        if (in_array($property, $this->imageFields)) {
            return $this->getImageUrl($property);
        }

        return $this->entity->$property;
    }

    public function __call($name, $arguments)
    {
        if (in_array($name, $this->imageFields)) {
            $width = 0;
            $height = 0;
            switch (count($arguments)) {
                case 0:
                    break;
                case 1:
                    $width = $arguments[0];
                    break;
                default:
                    $width = $arguments[0];
                    $height = $arguments[1];
                    break;
            }

            return $this->getImageUrl($name, $width, $height);
        }
    }

    protected function getImageUrl($property, $width = 0, $height = 0)
    {
        $defaultUrl = 'https://placehold.it/328x328?text=No%20Image';

        $camelName = \StringHelper::snake2Camel($property);
        /** @var Image $relation */
        $relation = $this->entity->$camelName;
        if (!empty($relation)) {
            return $relation->getThumbnailUrl($width, $height);
        }

        $property .= '_id';
        if (!$this->entity->$property) {
            return $defaultUrl;
        }
        /* @var \App\Models\Image $image */
        $imageRepository = \App::make(ImageRepositoryInterface::class);
        $image = $imageRepository->find($this->entity->$property);

        return $image ? $image->present()->url : $defaultUrl;
    }

}
