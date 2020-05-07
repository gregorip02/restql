<?php

namespace Restql\Clausules;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Restql\Clausule;
use Restql\ClausuleExecutor;
use Restql\Support\ReflectionSupport;

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
        return $this->arguments->filter(function ($null, $method) use ($model) {
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
            $parentClass = class_parents($methodType);
            $parentClass = array_values($parentClass)[count($parentClass) - 1];

            /// A stupid way to determine if the property typed in the method is
            /// of type "\Illuminate\Database\Eloquent\Relation".
            return $parentClass === Relation::class;
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
