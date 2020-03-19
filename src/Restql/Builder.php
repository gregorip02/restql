<?php

namespace Restql;

use Clousure;
use Restql\ClausuleExecutor;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class Builder
{
    /**
     * A collection of filters for queries.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $filters;

    /**
     * The eloquent query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Class instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Support\Collection $collection
     */
    public function __construct(QueryBuilder $query, Collection $filters)
    {
        $this->query = $query;
        $this->filters = $filters;
    }

    /**
     * Static class instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Support\Collection $collection
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function make(QueryBuilder $query, Collection $filters): QueryBuilder
    {
        return (new Builder(...func_get_args()))->dispatch();
    }

    /**
     * Run a new query.
     *
     * @param  Clousure $callback
     * @return void
     */
    public function executeQuery($callback): void
    {
        $callback($this->query);
    }

    /**
     * Chains the methods to the eloquent query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function dispatch(): QueryBuilder
    {
        $this->filters->each(function ($arguments, $clausule) {
            $this->add($clausule, collect($arguments));
        });

        return $this->query;
    }

    /**
     * Add a clause to the query.
     *
     * @param string $clausule
     * @param \Illuminate\Support\Collection $arguments
     * @return void
     */
    protected function add(string $clausule, Collection $arguments): void
    {
        ClausuleExecutor::exec($this, $clausule, $arguments);
    }
}
