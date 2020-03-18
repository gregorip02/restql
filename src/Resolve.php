<?php

namespace App\RestQL;

use App\RestQL\Builder;
use App\RestQL\RequestParser;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

trait Resolve
{
    /**
     * El nombre del paremetro que sera evaluado en la petición.
     *
     * @var string
     */
    protected $queryParam = 'query';

    /**
     * Punto de entrada de la resolución de atributos.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function resolve(Request $request): QueryBuilder
    {
        $filters = RequestParser::filter($request, $this->queryParam);

        return Builder::make($this->query(), $filters);
    }
}
