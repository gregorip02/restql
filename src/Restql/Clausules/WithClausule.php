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
            $query->with($this->args($query->getModel(), $attributes));
        });
    }

    /**
     * Filtra la lista de relaciones como llaves y callbacks como valores.
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
     * Construye la lista de relaciones como llaves y callbacks como valores.
     *
     * @param  \Illuminate\Support\Collection $clausules
     * @return Clousure
     */
    protected function buildRelationQuery(Collection $clausules)
    {
        return function (Relation $relation) use ($clausules) {
            $clausules->filter(function ($arguments, $clausule) {
                // Filtrar las clausulas que seran ejecutadas con respecto
                // a las clausulas aceptadas por la propiedad $accepted, en
                // la clase ClausuleExcecutor.
                return key_exists($clausule, ClausuleExecutor::$accepted);
            })
            ->each(function ($arguments, $clausule) use ($relation) {
                // Ejecutar la clausula con la query de la relación.
                $arguments = collect($arguments)->push(
                    // Por defecto se agrega el nombre de la llave relacionada
                    // con el modelo padre.
                    $relation->getForeignKeyName()
                );

                ClausuleExecutor::execWithQuery($relation->getQuery(), $clausule, $arguments);
            });
        };
    }
}
