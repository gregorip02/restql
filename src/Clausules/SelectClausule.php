<?php

namespace Restql\Clausules;

use Restql\ClausuleExecutor;
use Illuminate\Support\Collection;
use Restql\Contracts\ClausuleContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelectClausule implements ClausuleContract
{
    /**
     * {@inheritdoc}
     */
    public function build(ClausuleExecutor $executor, Collection $arguments): void
    {
        $executor->executeQuery(function ($query) use ($executor, $arguments) {
            /// Get the model associated with the eloquent query constructor
            $model = $executor->getModel();

            /// Get the arguments requested by the client
            $arguments = $this->parseArguments($model, $arguments);

            /// You have to determine if the client requests BelongsTo
            /// relationships in the "with" clause. If true, the foreign key
            /// name must be added to the query so that the eloquent collection
            /// knows where it belongs.
            $withModelNames = $executor->getWithModelKeyNames();
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
     * @param \Illuminate\Support\Collection $arguments
     * @return \Illuminate\Support\Collection
     */
    public function parseArguments(Model $model, Collection $arguments): Collection
    {
        $hidden = $model->getHidden();
        /// NEVER include the hidden attributes.
        return $arguments->forget($hidden)->add(
            $model->getKeyName()
        )->unique();
    }
}
