<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Parameter name sending in the request.
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
    | Define a list of the models that RestQL can manipulate, create
    | authorizers and middlewares to protect your schema definition
    | resources.
    |
    | See https://github.com/gregorip02/restql/tree/stable/docs/Schema.md
    */

    'schema' => [],

    /*
    |--------------------------------------------------------------------------
    | Custom resolvers definition
    |--------------------------------------------------------------------------
    |
    | Define custom data resolvers, you can also define permissions
    | and middlewares for clients to access it.
    |
    | See https://github.com/gregorip02/restql/tree/stable/docs/Resolvers.md
    */

    'resolvers' => [
        // Uncoment this and get the currently authenticated user.
        // 'whoami' => [
        //   'class' => 'Restql\Resolvers\WhoamiResolver',
        //   'authorizer' => 'Restql\Authorizers\WhoamiAuthorizer',
        //   'middlewares' => ['auth']
        // ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed clausules
    |--------------------------------------------------------------------------
    |
    | Define a list of clauses that are available. Modify or delete the clauses
    | that do not interest you.
    |
    | See https://github.com/gregorip02/restql/tree/stable/docs/Clausules.md
    */

    'clausules' => [
        'select' => 'Restql\Clausules\SelectClausule',
        'where' => 'Restql\Clausules\WhereClausule',
        'take' => 'Restql\Clausules\TakeClausule',
        'sort' => 'Restql\Clausules\SortClausule',
        'with' => 'Restql\Clausules\WithClausule',
        'create' => 'Restql\Clausules\CreateClausule',
    ]
];
