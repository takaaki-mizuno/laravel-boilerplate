<?php

namespace Tests\Models;

use App\Models\Article;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Article $article */
        $article = new Article();
        $this->assertNotNull($article);
    }

    public function testStoreNew()
    {
        /* @var  \App\Models\Article $article */
        $articleModel = new Article();

        $articleData = factory(Article::class)->make();
        foreach ($articleData->toFillableArray() as $key => $value) {
            $articleModel->$key = $value;
        }
        $articleModel->save();

        $this->assertNotNull(Article::find($articleModel->id));
    }
}
