<?php

use App\Author;
use Restql\Restql;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('restql', function (Request $request) {
    return Restql::resolve($request);
});

Route::get('version', function () {
    $app = app();
    return $app::VERSION;
});

Route::get('authors', function () {
    return Author::take(15)->get();
});
