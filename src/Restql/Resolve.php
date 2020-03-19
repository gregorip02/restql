<?php

namespace Restql;

use Restql\Builder;
use Restql\RequestParser;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

trait Resolve
{
    /**
     * The parameter name that will be evaluated in the request.
     *
     * @var string
     */
    protected $queryParam = 'query';

    /**
     * Start data resolution from the eloquent model.
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
