<?php

use App\Author;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Restql\Restql;

Route::get('restql', function (Request $request) {
    return Restql::resolve($request);
});

Route::get('version', function () {
    $app = app();
    return $app::VERSION;
});

Route::get('traditional', function () {
    return Author::with(['articles' => static function (Relation $relation) {
        $relation->with('comments');
    }])->take(15)->get();
});
