<?php

namespace App\Presenters;

class ImagePresenter extends BasePresenter
{
    public function url()
    {
        if ($this->entity->is_local == false) {
            return $this->entity->url;
        }

        return \URL::to($this->entity->url);
    }
}
