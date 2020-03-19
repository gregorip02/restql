<?php

namespace Restql;

use Restql\Builder;
use Restql\Clausules\OrderByClausule;
use Restql\Clausules\SelectClausule;
use Restql\Clausules\WithClausule;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ClausuleExecutor
{
    /**
     * The accepted clausules.
     *
     * @var array
     */
    public static $accepted = [
        'select' => SelectClausule::class,
        'orderBy' => OrderByClausule::class
    ];

    /**
     * The restql query builder.
     *
     * @var \Restql\Builder
     */
    protected $builder;

    /**
     * The eloquent clause received.
     *
     * @var string
     */
    protected $clausule;

    /**
     * The clausule arguments
     *
     * @var \Illuminate\Support\Collection
     */
    protected $arguments;

    /**
     * The class instance.
     *
     * @param Restql\Builder $builder
     * @param string $clausule
     * @param \Illuminate\Support\Collection $arguments
     */
    public function __construct(Builder $builder, string $clausule, Collection $arguments)
    {
        $this->builder = $builder;
        $this->clausule = $clausule;
        $this->arguments = $arguments;
    }

    /**
     * The static class instance.
     *
     * @param \Restql\Builder $builder
     * @param string $clausule
     * @param \Illuminate\Support\Collection $arguments
     * @return void
     */
    public static function exec(Builder $builder, string $clausule, Collection $arguments): void
    {
        (new ClausuleExecutor(...func_get_args()))->make();
    }

    /**
     * Create an instance of the clause and execute the build method.
     *
     * @return void
     */
    protected function make(): void
    {
        $clausule = app($this->getClausuleClass())->build(
            ...$this->getClausuleParams()
        );
    }

    /**
     * Gets the class that executes the query.
     *
     * @return string
     */
    protected function getClausuleClass(): string
    {
        return $this::$accepted[$this->clausule];
    }

    /**
     * Gets the arguments that all the clauses must receive by contract.
     *
     * @return array
     */
    protected function getClausuleParams(): array
    {
        return [$this->builder, $this->arguments];
    }
}
