<?php

namespace App\RestQL;

use App\RestQL\RequestConstrains;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Builder
{
    /**
     * Una colección de filtros para las consultas.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $collection;

    /**
     * El constructor de consultas.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Creando la instancia de la clase.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Support\Collection $collection
     */
    public function __construct(QueryBuilder $query, Collection $collection)
    {
        $this->query = $query;
        $this->collection = $collection;
    }

    /**
     * Crea una instancia a partir de un metodo estatico.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Support\Collection $collection
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function make(QueryBuilder $query, Collection $collection): QueryBuilder
    {
        return (new Builder(...func_get_args()))->dispatch();
    }

    /**
     * Encadena los metodos a la consulta de eloquent.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function dispatch(): QueryBuilder
    {
        $this->collection->each(function ($constrains, $clausule) {
            $this->query->{$clausule}(
                $this->getConstrainsByClausule($clausule, $constrains)
            );
        });

        return $this->query;
    }

    /**
     * Obtiene el modelo que actua como punto de entrada.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function parentModel(): Model
    {
        return $this->query->getModel();
    }

    /**
     * Obtiene el modelo relacionado al punto de entrada.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getRelatedModel(): Model
    {
        return $this->query->getRelated();
    }

    /**
     * Obtiene el nombre de la llavel primaria del modelo incial.
     *
     * @return string
     */
    public function parentModelKeyName(): string
    {
        return $this->parentModel()->getKeyName();
    }

    /**
     * Resuelve los argumentos correspondientes a una clausula dada.
     *
     * @param  string $clausule
     * @param  string|array $constrains
     * @return string|array
     */
    public function getConstrainsByClausule(string $clausule, $constrains)
    {
        return RequestConstrains::args($this, $clausule, $constrains);
    }
}
