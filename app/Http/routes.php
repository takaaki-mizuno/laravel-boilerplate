<?php

foreach ([
             \App\Http\Routes\Admin::class,
             \App\Http\Routes\User::class,
         ] as $routeClass) {
    $routeClass::add();
}

