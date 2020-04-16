<?php

namespace Restql;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Restql\Builder;
use Restql\Clausules\WhereClausule;
use Restql\Clausules\SelectClausule;
use Restql\Clausules\SortClausule;
use Restql\Clausules\WithClausule;

class ClausuleExecutor
{
    /**
     * The accepted clausules.
     *
     * @var array
     */
    public const ACCEPTED_CLAUSULES = [
        'select' => SelectClausule::class,
        'sort' => SortClausule::class,
        'with' => WithClausule::class,
        'where' => WhereClausule::class
    ];

    /**
     * The eloquent model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The eloquent query.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * The eloquent clauses.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $clausules;

    /**
     * The class instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Support\Collection $clausules
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function __construct(Model $model, Collection $clausules, QueryBuilder $query = null)
    {
        $this->model = $model;
        $this->query = $query ?? $model->query();
        $this->clausules = $clausules;
    }

    /**
     * Static class instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Support\Collection $clausules
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function exec(Model $model, Collection $clausules, QueryBuilder $query = null): QueryBuilder
    {
        return (new ClausuleExecutor(...func_get_args()))->make();
    }

    /**
     * Instance all the recived clausules and built it.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function make(): QueryBuilder
    {
        $this->clausules->each(function ($arguments, $clausuleName) {
            $clausuleClassName = $this->getClausuleClassName($clausuleName);
            if (class_exists($clausuleClassName)) {
                (new $clausuleClassName($this, collect($arguments)))->build();
            }
        });

        return $this->query;
    }

    /**
     * Get the clausule class name based on the clausule key name.
     *
     * @param  string $clausuleName
     * @return string
     */
    protected function getClausuleClassName(string $clausuleName): string
    {
        if (key_exists($clausuleName, self::ACCEPTED_CLAUSULES)) {
            return self::ACCEPTED_CLAUSULES[$clausuleName];
        }

        return '';
    }

    /**
     * Get the model instance being queried.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get the relations key name in the with clausule.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getWithModelKeyNames(): Collection
    {
        return collect(
            array_keys($this->clausules->get('with', []))
        );
    }

    /**
     * Execute and mutate the model query.
     *
     * @param  Clousure $callback
     * @return void
     */
    public function executeQuery($callback): void
    {
        $callback($this->query);
    }
}
