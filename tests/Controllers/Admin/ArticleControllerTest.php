<?php
namespace Tests\Controllers\Admin;

use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\ArticleController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\ArticleController::class);
        $this->assertNotNull($controller);
    }

    public function setUp()
    {
        parent::setUp();
        $authUser = \App\Models\AdminUser::first();
        $this->be($authUser, 'admins');
    }

    public function testGetList()
    {
        $response = $this->action('GET', 'Admin\ArticleController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\ArticleController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $article = factory(\App\Models\Article::class)->make();
        $postData = $article->toFillableArray();
        $postData['publish_started_at'] = $postData['publish_started_at']->format('Y-m-d H:m:s');
        $this->action('POST', 'Admin\ArticleController@store', [
                '_token' => csrf_token(),
            ] + $postData);
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $article = factory(\App\Models\Article::class)->create();
        $this->action('GET', 'Admin\ArticleController@show', [$article->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $article = factory(\App\Models\Article::class)->create();

        $title = $faker->sentence;
        $id = $article->id;

        $article->title = $title;

        $this->action('PUT', 'Admin\ArticleController@update', [$id], [
                '_token' => csrf_token(),
            ] + $article->toFillableArray());
        $this->assertResponseStatus(302);

        $newArticle = \App\Models\Article::find($id);
        $this->assertEquals($title, $newArticle->title);
    }

    public function testDeleteModel()
    {
        $article = factory(\App\Models\Article::class)->create();

        $id = $article->id;

        $this->action('DELETE', 'Admin\ArticleController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkArticle = \App\Models\Article::find($id);
        $this->assertNull($checkArticle);
    }
}
