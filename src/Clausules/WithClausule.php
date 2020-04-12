<?php

namespace Restql\Clausules;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
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
            /// Determine if the relationship does not exists.
            if (!method_exists($model, $relationName)) {
                return false;
            }

            /// Determine if the relationship is of type Relation.
            return $model->{$relationName}() instanceof Relation;
        })->map(function ($clausules) {
            /// Build the related model query.
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
            if ($args = $clausules->get('select', false)) {
                /// Add the foreign key name in HasMany type relationships
                if ($relation instanceof HasMany) {
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
