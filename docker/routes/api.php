<?php

use App\Author;
use Restql\Restql;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Relations\Relation;

Route::any('restql', function (Request $request) {
    return Restql::resolve($request);
});

Route::get('version', function () {
    $app = app();
    return $app::VERSION;
});

Route::get('traditional', function () {
    $authors = Author::with(['articles' => static function (Relation $relation) {
        $relation->with('comments');
    }])->take(15)->get();

    return ['data' => compact('authors')];
});
