<?php

namespace Restql\Clausules;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Restql\Argument;
use Restql\Arguments\SelectArgument;
use Restql\Clausule;
use Restql\Support\ReflectionSupport;

class SelectClausule extends Clausule
{
    /**
     * The allowed verbs for a determinated clausule.
     *
     * @var array
     */
    protected $allowedVerbs = ['all'];

    /**
     * Implement the clausule query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function build(QueryBuilder $builder): void
    {
        $arguments = $this->arguments->data();

        /// You have to determine if the client requests BelongsTo
        /// relationships in the next "with" clause. If true, the foreign key
        /// name must be added to the query so that the eloquent collection
        /// knows where it belongs.
        $this->pushBelongsToForeignKeyName($arguments);

        $builder->select($arguments);
    }

    /**
     * Push belongsTo foreign key names to the selec clausule.
     *
     * @param  array &$arguments
     * @return void
     */
    protected function pushBelongsToForeignKeyName(array &$arguments): void
    {
        $withModelNames = $this->executor->getWithModelKeyNames();

        if ($withModelNames->count()) {
            $belongsTo = $this->getBelongsToAttributes($withModelNames);
            if (count($belongsTo)) {
                foreach ($belongsTo as $belongsToAttribute) {
                    $arguments[] = $belongsToAttribute;
                }
            }
        }
    }

    /**
     * Gets the names of the foreign keys for relationships of type BelongsTo.
     *
     * @param  \Illuminate\Support\Collection $withParams
     * @return array
     */
    protected function getBelongsToAttributes(Collection $withParams): array
    {
        $model = $this->executor->getModel();
        return $withParams->filter(function ($method) use ($model) {
            if (! method_exists($model, $method)) {
                /// Exclude methods not found in the model
                return false;
            }

            /// From this change, the developer needs to set the return type in
            /// its eloquent relationships. This will greatly optimize the
            /// algorithm speed and consequently server responses.
            return (new ReflectionSupport($model))->methodIs($method, BelongsTo::class);
        })->map(function ($method) use ($model) {
            /// Get the foreign key of the relationship.
            return call_user_func([$model, $method])->getForeignKeyName();
        })->unique()->toArray();
    }

    /**
     * Get a new instance of the clausule argument.
     *
     * @param  array  $values
     * @return \Restql\Argument
     */
    protected function createArgumentsInstance(array $values = []): Argument
    {
        return new SelectArgument($this->executor->getModel(), $values);
    }

    /**
     * Throw a exception if can't build this clausule.
     *
     * @return void
     */
    protected function canBuild(): void
    {
        parent::throwIfMethodIsNotAllowed('select');
    }
}
