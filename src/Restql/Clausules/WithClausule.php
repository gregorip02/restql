<?php

namespace Restql\Clausules;

use Restql\Builder;
use Restql\ClausuleExecutor;
use Restql\Contracts\ClausuleContract;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class WithClausule implements ClausuleContract
{
    /**
     * {@inheritdoc}
     */
    public function build(Builder $builder, Collection $attributes): void
    {
        $builder->executeQuery(function (QueryBuilder $query) use ($attributes) {
            $arguments = $this->args($query->getModel(), $attributes);

            $query->with($arguments);
        });
    }

    /**
     * Create an array that corresponds to the relation name => callback.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  \Illuminate\Support\Collection $attributes
     * @return array
     */
    protected function args(Model $model, Collection $attributes): array
    {
        return $attributes->filter(function ($clausules, $relation) use ($model) {
            // Devolver solo las relaciones existentes para el modelo.
            return method_exists($model, $relation);
        })->map(function ($clausules) {
            // Ejecutar los callbacks, probablemente mas clausulas. Estas
            // serán añadidas a la relación en vez del modelo padre.
            return $this->buildRelationQuery(collect($clausules));
        })->toArray();
    }

    /**
     * Returns the callback with the list of queries generated for the relation.
     *
     * @param  \Illuminate\Support\Collection $clausules
     * @return Clousure
     */
    protected function buildRelationQuery(Collection $clausules)
    {
        return function (Relation $relation) use ($clausules) {
            $clausules->filter(function ($arguments, $clausule) {
                // Filter the clauses that will be executed with respect
                // to the clauses accepted by the property $accepted, in
                // the ClausuleExcecutor class.
                return key_exists($clausule, ClausuleExecutor::$accepted);
            })
            ->each(function ($arguments, $clausule) use ($relation) {
                $arguments = collect($arguments)->push(
                    // By default the name of the related key is added.
                    $relation->getForeignKeyName()
                );

                // Execute the clause with the relation query.
                ClausuleExecutor::execWithQuery($relation->getQuery(), $clausule, $arguments);
            });
        };
    }
}
