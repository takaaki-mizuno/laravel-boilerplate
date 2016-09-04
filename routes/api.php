<?php

use Illuminate\Http\Request;

Route::get('/me', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
