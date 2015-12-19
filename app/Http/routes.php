<?php

Route::group(['prefix' => 'admin', 'middleware' => ['admin.guest']], function () {
    Route::get('signin', 'Admin\AuthController@getSignIn');
    Route::post('signin', 'Admin\AuthController@postSignIn');
    Route::get('forgot-password', 'Admin\AuthController@getForgotPassword');
    Route::post('forgot-password', 'Admin\AuthController@postForgotPassword');
});

Route::group(['prefix' => 'admin', 'middleware' => ['admin.auth', 'admin.values']], function () {
    Route::get('/', 'Admin\IndexController@index');
});

Route::group(['middleware' => ['user.values']], function () {
    Route::get('/', 'User\IndexController@index');
});
