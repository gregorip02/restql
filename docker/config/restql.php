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
    | Define customizable resolvers,
    */

    'resolvers' => [
        // Uncoment this and get the currently authenticated user.
        'whoami' => [
           'class' => 'Restql\Resolvers\WhoamiResolver',
           'authorizer' => 'Restql\Authorizers\WhoamiAuthorizer',
           'middlewares' => []
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
        'with' => 'Restql\Clausules\WithClausule',
        /// Mutations clausules
        'create' => 'Restql\Clausules\CreateClausule',
    ]
];
