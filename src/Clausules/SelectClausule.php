<?php

namespace Restql\Clausules;

use Restql\Clausule;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class SelectClausule extends Clausule
{
    /**
     * Implement the clausule query builder.
     *
     * @return void
     */
    public function build(): void
    {
        $this->executor->executeQuery(function (QueryBuilder $query) {
            /// Get the model associated with the eloquent query constructor
            $model = $this->executor->getModel();

            /// Get the arguments requested by the client
            $arguments = $this->parseArguments($model);

            /// You have to determine if the client requests BelongsTo
            /// relationships in the "with" clause. If true, the foreign key
            /// name must be added to the query so that the eloquent collection
            /// knows where it belongs.
            $withModelNames = $this->executor->getWithModelKeyNames();
            if ($withModelNames->count()) {
                $belongsTo = $this->getBelongsToAttributes($withModelNames, $model);
                if (count($belongsTo)) {
                    $arguments->push(...$belongsTo);
                }
            }

            $query->select($arguments->toArray());
        });
    }

    /**
     * Gets the names of the foreign keys for relationships of type BelongsTo.
     *
     * @param  \Illuminate\Support\Collection $withParams
     * @param  \Illuminate\Database\Model $model
     *
     * @return array
     */
    protected function getBelongsToAttributes(Collection $withParams, Model $model): array
    {
        return $withParams->filter(function ($method) use ($model) {
            /// Determine if the relationship does not exists.
            if (!method_exists($model, $method)) {
                return false;
            }
            /// Determine if the relationship is of type BelongsTo.
            return $model->{$method}() instanceof BelongsTo;
        })->map(function ($method) use ($model) {
            /// Get the foreign key of the relationship.
            return $model->{$method}()->getForeignKeyName();
        })->unique()->toArray();
    }

    /**
     * Get the select arguments requested by the client.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Support\Collection
     */
    public function parseArguments(Model $model): Collection
    {
        $hidden = $model->getHidden();
        /// NEVER include the hidden attributes.
        return $this->arguments->forget($hidden)->add(
            $model->getKeyName()
        )->unique();
    }
}
