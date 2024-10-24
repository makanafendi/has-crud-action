<?php

namespace SmartPOS\HasCrudAction;

use Illuminate\Support\Facades\Route;

class HasCrudAction
{
    public function route(string $route, string $controller)
    {
        Route::resource($route, $controller);
    }
}
