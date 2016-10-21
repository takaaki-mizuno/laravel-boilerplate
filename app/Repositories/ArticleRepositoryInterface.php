<?php

namespace App\Repositories;

interface ArticleRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param $slug
     *
     * @return mixed
     */
    public function findBySlug($slug);
}
