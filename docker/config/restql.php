<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Nombre del atributo enviado en la petición.
    |--------------------------------------------------------------------------
    |
    | Esta opción controla el nombre del parametro recibido en la petición HTTP
    | que RestQL interceptará como los filtros del constructor de consultas.
    |
    | Si el valor de este parametro esta vació, RestQL asumirá que los datos
    | son enviados en el cuerpo de la petición.
    */

    'query_param' => env('RESTQL_PARAM_NAME', ''),

    /*
    |--------------------------------------------------------------------------
    | Modelos de resolución de datos.
    |--------------------------------------------------------------------------
    |
    | Defina una lista que represente un nombre de acceso como llave y una clase
    | de su modelo eloquent explicitamente. Los modelos definidos aqui, estarán
    | disponibles para la resolución automatica de datos.
    |
    | @example 'authors' => 'App\Author'
    */
    'allowed_models' => [
        'authors' => 'App\Author'
    ]
];
