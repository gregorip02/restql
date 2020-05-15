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
    | Data resolution schema
    |--------------------------------------------------------------------------
    |
    | Define a list of models allowed for automatic manipulation, set the
    | permissions and the actions that your users can take on it.
    */

    'schema' => [
        // 'authors' => [
        //    'class'  => 'App\Author'
        // ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom resolvers definition
    |--------------------------------------------------------------------------
    |
    | Define customizable resolvers,
    */

    'resolvers' => [
        // Uncoment this and get the currently authenticated user.
        // 'whoami' => [
        //    'class' => 'Restql\Resolvers\WhoamiResolver',
        // ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed clausules
    |--------------------------------------------------------------------------
    |
    | Define a list of clauses that are available. Modify or delete the clauses
    | that do not interest you.
    */
    'clausules' => [
        'select' => 'Restql\Clausules\SelectClausule',
        'where' => 'Restql\Clausules\WhereClausule',
        'take' => 'Restql\Clausules\TakeClausule',
        'sort' => 'Restql\Clausules\SortClausule',
        'with' => 'Restql\Clausules\WithClausule'
    ]
];
