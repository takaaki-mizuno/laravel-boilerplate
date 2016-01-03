<?php namespace App\Presenters;

use App\Models\Base;
use McCool\LaravelAutoPresenter\BasePresenter as OriginalBasePresenter;

class BasePresenter extends OriginalBasePresenter
{
    public function __construct(Base $resource)
    {
        $this->wrappedObject = $resource;
    }

}