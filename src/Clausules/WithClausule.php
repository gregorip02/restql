<?php

namespace Restql\Clausules;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Restql\Clausule;
use Restql\Support\ReflectionSupport;

class WithClausule extends Clausule
{
    /**
     * Implement the clausule query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
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
     * @return array
     */
    protected function parseArguments(Model $model): array
    {
        return $this->getArgument()->getCollectionValues()->filter(function ($null, $method) use ($model) {
            if (! method_exists($model, $method)) {
                /// Exclude methods not found in the model
                return false;
            }

            $reflection = new ReflectionSupport($model);
            $methodType = $reflection->getMethodReturnType($method);

            /// Excluding untyped methods in eloquent models.
            if ($methodType === '') {
                return false;
            }

            /// From this change, the developer needs to set the return type in
            /// its eloquent relationships. This will greatly optimize the
            /// algorithm speed and consequently server responses.
            $parentClasses = class_parents($methodType) ?? [];

            /// A stupid way to determine if the property typed in the method is
            /// of type "\Illuminate\Database\Eloquent\Relation".
            return array_key_exists(Relation::class, $parentClasses);
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
            if ($selects = (array) $clausules->get('select', null)) {
                /// Add the foreign key name in HasMany type relationships
                if ($relation instanceof HasMany) {
                    $foreignKeyName = $relation->getForeignKeyName();
                    if (! in_array($foreignKeyName, $selects, true)) {
                        $selects[] = $foreignKeyName;
                        $clausules->put('select', $selects);
                    }
                }
            }

            /// Unnecesary include the take or limit clausule.
            $clausules->forget(['take', 'limit']);

            $this->executor->exec($relation->getRelated(), $clausules, $relation->getQuery());
        };
    }
}
