<?php

namespace Restql;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Restql\ClausuleExecutor;
use Restql\Services\ConfigService;

class Builder
{
    /**
     * A query collection of models.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $query;

    /**
     * The array response.
     *
     * @var array
     */
    protected $response = [];

    /**
     * The application config.
     *
     * @var \Restql\Services\ConfigService
     */
    protected $config;

    /**
     * Builder instance.
     *
     * @param \Illuminate\Support\Collection $query
     */
    public function __construct(Collection $query)
    {
        $this->query = $query;

        $this->config = app(ConfigService::class);
    }

    /**
     * Static class instance.
     *
     * @param \Illuminate\Support\Collection $query
     * @return \Illuminate\Support\Collection
     */
    public static function make(Collection $query): Collection
    {
        return (new Builder($query))->dispatch();
    }

    /**
     * Chains the methods to the eloquent query.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function dispatch(): Collection
    {
        $this->getAllowedModels()->each(function ($clausules, $modelKeyName) {
            /// Obtain the class name of the eloquent model based on the models
            /// allowed for the automatic resolution of data registered in the
            /// user configuration.
            $modelClassName = $this->getModelClassName($modelKeyName);

            if (class_exists($modelClassName)) {
                /// Determine if the class exists and is an instance of Model.
                $model = app($modelClassName);
                if ($model instanceof Model) {
                    /// Execute only the clauses allowed by RestQL.
                    /// TODO: Allow the user to create their own clauses.
                    $executor = $this->runExecutor($model, (array) $clausules);

                    /// Build the answer collection.
                    $this->response[$modelKeyName] = $executor;
                }
            }
        });

        return collect($this->response);
    }

    /**
     * Dispatch the clausule executor with the filter clausules.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $clausules
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function runExecutor(Model $model, array $clausules): QueryBuilder
    {
        return ClausuleExecutor::exec($model, $this->filterClausules($clausules));
    }

    /**
     * Filter the incoming clausules.
     *
     * @param  array  $incomingClausules
     * @return \Illuminate\Support\Collection
     */
    public function filterClausules(array $incomingClausules): Collection
    {
        return collect(ClausuleExecutor::filterClausules($incomingClausules));
    }

    /**
     * Get the allowed models by the developer.
     *
     * @return array
     */
    protected function getModelKeysNames(): array
    {
        return $this->config->get('allowed_models', []);
    }

    /**
     * Determine if the model key name is allowed.
     *
     * @param  string $modelKeyName
     * @return bool
     */
    protected function modelKeyNameExists(string $modelKeyName): bool
    {
        return array_key_exists($modelKeyName, $this->getModelKeysNames());
    }

    /**
     * Get the model classname for the instance.
     *
     * @param  string $modelKeyName
     * @return string
     */
    protected function getModelClassName(string $modelKeyName): string
    {
        return $this->getModelKeysNames()[$modelKeyName];
    }

    /**
     * Remove unknow model key names from the incoming query.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllowedModels(): Collection
    {
        return $this->query->filter(function ($null, $modelKeyName) {
            return $this->modelKeyNameExists($modelKeyName);
        });
    }
}
