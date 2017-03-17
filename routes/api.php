<?php

\Route::group(['prefix' => 'api', 'middleware' => []], function () {

    \Route::group(['middleware' => []], function () {

        \Route::group(['prefix' => 'v1', 'middleware' => []], function() {
            \Route::post('signin', 'API\V1\AuthController@signIn');

            \Route::post('signin/{social}', 'API\V1\AuthController@signInBySocial');

            \Route::post('forgot-password', 'API\V1\PasswordController@forgotPassword');

            \Route::post('signup', 'API\V1\AuthController@signUp');
        });

    });

    \Route::group(['middleware' => ['api.auth']], function () {

        \Route::group(['prefix' => 'v1', 'middleware' => []], function() {
            \Route::resource('articles', 'API\V1\ArticleController');

            \Route::group(['prefix' => 'profile'], function() {
                \Route::get('/getInfo', 'API\V1\UserController@show');
                \Route::put('/update', 'API\V1\UserController@update');
            });

            \Route::post('signout', 'API\V1\AuthController@postSignOut');
        });

    });
});

