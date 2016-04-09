<?php namespace App\Presenters;

use App\Models\File;

class FilePresenter extends BasePresenter
{
    public function __construct(File $resource)
    {
        $this->wrappedObject = $resource;
    }

    public function url()
    {
        if ($this->wrappedObject->is_local == false) {
            return $this->wrappedObject->url;
        }

        return \URL::to($this->wrappedObject->url);
    }

}
