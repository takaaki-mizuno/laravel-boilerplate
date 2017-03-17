<?php

\Route::group(['prefix' => 'api', 'middleware' => []], function () {

    \Route::group(['middleware' => []], function () {
        \Route::post('signin', 'API\AuthController@signIn');

        \Route::post('signin/{social}', 'API\AuthController@signInBySocial');

        \Route::post('forgot-password', 'API\PasswordController@forgotPassword');

        \Route::post('signup', 'API\AuthController@signUp');

    });

    \Route::group(['middleware' => ['api.auth']], function () {

        \Route::resource('articles', 'API\ArticleController');

        \Route::post('signout', 'API\AuthController@postSignOut');
    });
});

