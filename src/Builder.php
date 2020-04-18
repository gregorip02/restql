<?php

namespace Restql;

use Restql\ClausuleExecutor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

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
     * @var \Illuminate\Support\Collection
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

        $this->config = collect(Config::get('restql', []));
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
        $this->query->each(function ($clausules, $modelKeyName) {
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
                    $builder = ClausuleExecutor::exec($model, collect($clausules)->only(
                        array_keys(ClausuleExecutor::ACCEPTED_CLAUSULES)
                    ));

                    /// Build the answer collection.
                    $this->response[$modelKeyName] = $builder;
                }
            }
        });

        return collect($this->response);
    }

    /**
     * Get the model classname for the instance.
     *
     * @param  string $modelKeyName
     * @return string
     */
    protected function getModelClassName($modelKeyName): string
    {
        $config = $this->config->get('allowed_models', [])[$modelKeyName];
        return $config;
    }
}
