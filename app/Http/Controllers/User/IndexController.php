<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('pages.user.index', [
        ]);
    }
}
