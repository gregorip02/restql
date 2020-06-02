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

    'query_param' => env('RESTQL_PARAM_NAME', 'query'),

    /*
    |--------------------------------------------------------------------------
    | Data resolution schema
    |--------------------------------------------------------------------------
    |
    | Define a list of the models that RestQL can manipulate, create
    | authorizers and middlewares to protect your schema definition
    | resources.
    */

    'schema' => [
        'authors' => [
            'class' => 'App\Author',
            'authorizer' => 'App\Restql\Authorizers\AuthorAuthorizer',
            'middlewares' => []
        ],
        'articles' => [
            'class' => 'App\Article',
            'authorizer' => 'App\Restql\Authorizers\ArticleAuthorizer',
            'middlewares' => []
        ],
        'comments' => [
            'class' => 'App\Comment',
            'authorizer' => 'App\Restql\Authorizers\CommentAuthorizer',
            'middlewares' => []
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom resolvers definition
    |--------------------------------------------------------------------------
    |
    | Define custom data resolvers, you can also define permissions
    | and middlewares for clients to access it.
    */

    'resolvers' => [
        'whoami' => [
           'class' => 'Restql\Resolvers\WhoamiResolver',
           'authorizer' => 'Restql\Authorizers\WhoamiAuthorizer',
           'middlewares' => ['auth']
        ]
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
        'with' => 'Restql\Clausules\WithClausule',
        'create' => 'Restql\Clausules\CreateClausule'
    ]
];
