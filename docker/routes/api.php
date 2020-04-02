<?php

use Restql\Restql;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('restql', function (Request $request) {
    return Restql::resolve($request);
});
