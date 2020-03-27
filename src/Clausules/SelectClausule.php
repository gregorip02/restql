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
            /// Obtener el modelo padre del constructor de consultas eloquent.
            /// Con esto determinamos los attributos que pueden ser consultados
            /// por el cliente.
            $model = $executor->getModel();

            /// En primera instancia es necesario añadir los atributos
            /// solicitados por el cliente.
            $arguments = $this->parseArguments($model, $arguments);

            /// Es necesario determinar si se estan queriendo obtener
            /// datos de una relación de tipo BelongsTo, de ser verdadero
            /// es oportuno seleccionar el nombre de la llave foranea en la
            /// relación.
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
            /// esta definida en el modelo padre.
            if (!method_exists($model, $method)) {
                return false;
            }
            /// En caso de que el metodo exista, hay que determinar
            /// si su valor de retorno es una relación de tipo
            /// BelongsTo.
            return $model->{$method}() instanceof BelongsTo;
        })->map(function ($method) use ($model) {
            /// Get the foreign key of the relationship.
            return $model->{$method}()->getForeignKeyName();
        })->unique()->toArray();
    }

    /**
     * Get the select arguments.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Support\Collection $arguments
     * @return \Illuminate\Support\Collection
     */
    public function parseArguments(Model $model, Collection $arguments): Collection
    {
        $hidden = $model->getHidden();

        return $arguments->forget($hidden)->add(
            $model->getKeyName()
        )->unique();
    }
}
