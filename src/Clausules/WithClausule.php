<?php

namespace Restql\Clausules;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Restql\Clausule;
use Restql\ClausuleExecutor;

class WithClausule extends Clausule
{
    /**
     * Implement the clausule query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return void
     */
    public function build(QueryBuilder $builder): void
    {
        $arguments = $this->parseArguments($this->executor->getModel());

        $builder->with($arguments);
    }

    /**
     * Create an array that corresponds to the relation key name => callback.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    protected function parseArguments(Model $model): array
    {
        return $this->arguments->filter(function ($null, $relationName) use ($model) {
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
     *
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

            /// Unnecesary include the take or limit clausule.
            $clausules->forget(['take', 'limit']);

            ClausuleExecutor::exec($relation->getRelated(), $clausules, $relation->getQuery());
        };
    }
}
