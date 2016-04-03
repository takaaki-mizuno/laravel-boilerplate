<?php namespace App\Presenters;

use App\Models\Image;

class ImagePresenter extends BasePresenter
{
    public function __construct(Image $resource)
    {
        $this->wrappedObject = $resource;
    }

    public function url()
    {
        if( $this->wrappedObject->is_local == false ) {
            return $this->wrappedObject->url;
        }
        return \URL::to($this->wrappedObject->url);
    }

}
