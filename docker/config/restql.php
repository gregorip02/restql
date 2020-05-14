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
    | Define your schema, include the allowed models.
    */

    'schema' => [
        'authors' => [
            'class' => 'App\Author'
        ],
        'articles' => [
            'class' => 'App\Article'
        ],
        'comments' => [
            'class' => 'App\Comment'
        ]
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
        'whoami' => [
           'class' => 'Restql\Resolvers\WhoamiResolver',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed clausules
    |--------------------------------------------------------------------------
    |
    | TODO: Create documentation for this.
    */
    'clausules' => [
        'select' => 'Restql\Clausules\SelectClausule',
        'where' => 'Restql\Clausules\WhereClausule',
        'take' => 'Restql\Clausules\TakeClausule',
        'sort' => 'Restql\Clausules\SortClausule',
        'with' => 'Restql\Clausules\WithClausule'
    ]
];
