<?php

namespace Restql;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Restql\Services\ConfigService;
use Restql\Traits\HasConfigService;

final class ClausuleExecutor
{
    use HasConfigService;

    /**
     * The eloquent model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The eloquent clauses.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $clausules;

    /**
     * The eloquent query.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

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

        $this->clausules = $clausules;

        $this->query = $query ?? $model->query();
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
        $configService = $this->getConfigService();

        $this->clausules
        ->filter(function ($null, $clausuleName) use ($configService) {
            return $configService->hasClausule($clausuleName);
        })
        ->map(function ($arguments, string $clausuleName) use ($configService) {
            return $configService->createClasuleInstance($this, $clausuleName, (array) $arguments);
        })->each(function (Clausule $clausule) {
            $clausule->prepare();
        });

        return $this->query;
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
