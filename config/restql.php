<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Name of the parameter intercepted in the request
    |--------------------------------------------------------------------------
    |
    | If the value of this parameter is empty, RestQL will assume that the data
    | is sent in the body of the request.
    */

    'query_param' => env('RESTQL_PARAM_NAME', ''),

    /*
    |--------------------------------------------------------------------------
    | Data resolution models
    |--------------------------------------------------------------------------
    |
    | An associative array containing the name to access the model as a key and
    | the eloquent model class as a value.
    |
    | @example [ 'authors' => 'App\Author', 'articles' => 'App\Article' ]
    */
    'allowed_models' => [
        //
    ]
];
