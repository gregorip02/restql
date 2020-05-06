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

            /// Un pequeño truco para determinar si el metodo definido en el modelo
            /// Eloquent retorna una instancia de "Relation".
            ///
            /// La comprobación que se hace aqui parece algo estupida, ya que
            /// solor verifica si el tipo de retorno del metodo existe en el namespace
            /// "Illuminate\Database\Eloquent\Relation\<ReturnType>".
            ///
            /// Con esto evitamos que se cuelen "relaciones" que en realidad no
            /// lo son. Por ejemplo, un desarrollador podria enviar "with" como
            /// un metodo mas en el conjunto de metodos aceptados por la clausula
            /// "WithClausule" y este intentaria ejecutarlo pensando que es una
            /// relación.
            ///
            /// From this change, the developer needs to set the return type in
            /// its eloquent relationships. This will greatly optimize the
            /// algorithm speed and consequently server responses.
            return class_exists(
                Str::replaceLast('Relation', class_basename($methodType), Relation::class)
            );
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
