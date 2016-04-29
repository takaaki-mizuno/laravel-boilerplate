<?php

Route::group(['prefix' => 'admin', 'middleware' => ['admin.values']], function () {

    Route::group(['middleware' => ['admin.guest']], function () {
        Route::get('signin', 'Admin\AuthController@getSignIn');
        Route::post('signin', 'Admin\AuthController@postSignIn');
        Route::get('forgot-password', 'Admin\PasswordController@getForgotPassword');
        Route::post('forgot-password', 'Admin\PasswordController@postForgotPassword');
        Route::get('reset-password/{token}', 'Admin\PasswordController@getResetPassword');
        Route::post('reset-password', 'Admin\PasswordController@postResetPassword');
    });

    Route::group(['middleware' => ['admin.auth']], function () {
        Route::get('/', 'Admin\IndexController@index');

        Route::resource('users', 'Admin\UserController');
        Route::resource('admin-users', 'Admin\AdminUserController');
        Route::resource('site-configurations', 'Admin\SiteConfigurationController');
        /* NEW ADMIN RESOURCE ROUTE */

    });
});

Route::group(['middleware' => ['user.values']], function () {
    Route::get('/', 'User\IndexController@index');

    Route::group(['middleware' => ['user.guest']], function () {
        Route::get('signin', 'User\AuthController@getSignIn');
        Route::post('signin', 'User\AuthController@postSignIn');

        Route::get('signin/facebook', 'User\FacebookServiceAuthController@redirect');
        Route::get('signin/facebook/callback', 'User\FacebookServiceAuthController@callback');

        Route::get('forgot-password', 'User\PasswordController@getForgotPassword');
        Route::post('forgot-password', 'User\PasswordController@postForgotPassword');

        Route::get('reset-password/{token}', 'User\PasswordController@getResetPassword');
        Route::post('reset-password', 'User\PasswordController@postResetPassword');

    });

    Route::group(['middleware' => ['user.auth']], function () {

    });

});
