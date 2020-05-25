<?php

namespace Restql;

use Closure;
use Restql\Traits\HasConfigService;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

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

        $this->clausules->filter(function ($_, $clausuleKeyName) use ($configService) {
            /// Determine if a key or className is registered in the config.
            return $configService->hasClausule($clausuleKeyName);
        })->map(function ($arguments, string $clausuleKeyName) use ($configService) {
            /// Create a instance of Clausule based on the key name.
            return $configService->createClasuleInstance($this, $clausuleKeyName, (array) $arguments);
        })->each(function (Clausule $clausule) {
            /// Prepare and run the clausule builder method.
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
        $withKeys = array_keys($this->clausules->get('with', []));

        return Collection::make($withKeys);
    }

    /**
     * Execute and mutate the model query.
     *
     * @param  Closure $callback
     * @return void
     */
    public function executeQuery(Closure $callback): void
    {
        $callback($this->query);
    }
}
