<?php

namespace Restql\Clausules;

use Restql\Builder;
use Illuminate\Support\Collection;
use Restql\Contracts\ClausuleContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelectClausule implements ClausuleContract
{
    /**
     * {@inheritdoc}
     */
    public function build(Builder $builder, Collection $attributes): void
    {
        $builder->executeQuery(function ($query) use ($builder, $attributes) {
            /// Obtener el modelo padre del constructor
            /// de consultas eloquent. Con esto determinamos los attributos
            /// que pueden ser consultados por el cliente.
            $model = $query->getModel();

            /// En primera instancia es necesario añadir los atributos
            /// solicitados por el cliente.
            $attributes = $this->parseAttributes($model, $attributes);

            /// Es necesario determinar si se estan queriendo obtener
            /// datos de una relación de tipo BelongsTo, de ser verdadero
            /// es oportuno seleccionar el nombre de la llave foranea en la
            /// relación.
            if ($with = $builder->getOffsetContext()->get('with')) {
                $with = collect($with)->keys();
                $attributes->push(...$this->getBelongsToAttributes($with, $model));
            }

            $query->select($attributes->toArray());
        });
    }

    /**
     * Obtiene los nombres de las llaves foraneas para relaciones de tipo BelongsTo.
     *
     * @param  \Illuminate\Support\Collection $withParams
     * @param  \Illuminate\Database\Model $model
     * @return array
     */
    protected function getBelongsToAttributes(Collection $withParams, Model $model): array
    {
        return $withParams->filter(function ($method) use ($model) {
            /// Es necesario determinar si la relación solicitada
            /// esta definida en el modelo padre. De ser verdadero,
            /// determinar si es una relación de tipo BelongsTo.
            if (method_exists($model, $method)) {
                return $model->{$method}() instanceof BelongsTo;
            }

            return false;
        })->map(function ($method) use ($model) {
            /// Get the foreign key of the relationship.
            return $model->{$method}()->getForeignKeyName();
        })->unique()->toArray();
    }

    /**
     * Get the select arguments.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Support\Collection $attributes
     * @return Collection
     */
    public function parseAttributes(Model $model, Collection $attributes): Collection
    {
        $hidden = $model->getHidden();

        return $attributes->filter(function ($value, $key) use ($hidden) {
            // Don't include hiddens attributes or associative values
            // on the select.
            return is_numeric($key) && !in_array($value, $hidden);
        })
        // Add the primary key name for every select.
        ->add($model->getKeyName())->unique();
    }
}
