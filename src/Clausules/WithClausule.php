<?php

namespace Restql\Clausules;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Restql\ClausuleExecutor;
use Restql\Contracts\ClausuleContract;

class WithClausule implements ClausuleContract
{
    /**
     * {@inheritdoc}
     */
    public function build(ClausuleExecutor $executor, Collection $arguments): void
    {
        $executor->executeQuery(function (QueryBuilder $query) use ($arguments, $executor) {
            $arguments = $this->args($executor->getModel(), $arguments);

            $query->with($arguments);
        });
    }

    /**
     * Create an array that corresponds to the relation key name => callback.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  \Illuminate\Support\Collection $arguments
     * @return array
     */
    protected function args(Model $model, Collection $arguments): array
    {
        return $arguments->filter(function ($null, $relationName) use ($model) {
            if (!method_exists($model, $relationName)) {
                return false;
            }

            return $model->{$relationName}() instanceof Relation;
        })->map(function ($clausules) {
            // Ejecutar los callbacks, probablemente mas clausulas. Estas
            // serán añadidas a la relación en vez del modelo padre.
            return $this->buildRelationQuery(collect($clausules));
        })->toArray();
    }

    /**
     * Build the clousure called in the select clausule.
     *
     * @param  \Illuminate\Support\Collection $clausules
     * @return Clousure
     */
    protected function buildRelationQuery(Collection $clausules)
    {
        return function (Relation $relation) use ($clausules) {
            /// Cuando se intentan obtener datos de una relación de tipo
            /// HasMany, es requerido añadir el nombre de la llave foranea
            /// de la relación para que los datos puedan ser mapeados
            /// correctamente por Laravel.
            if ($args = $clausules->get('select', false)) {
                if ($relation instanceof HasMany) {
                    /// Añade el nombre de la llave foranea a los argumentos
                    /// de la clausula select.
                    $args = collect($args)->add(
                        $relation->getForeignKeyName()
                    )->toArray();

                    $clausules->offsetSet('select', $args);
                }
            }

            ClausuleExecutor::exec($relation->getRelated(), $clausules, $relation->getQuery());
        };
    }
}
