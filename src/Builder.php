<?php

namespace Restql;

use Restql\ClausuleExecutor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class Builder
{
    /**
     * A query collection of models.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $query;

    /**
     * The array response.
     *
     * @var array
     */
    protected $response = [];

    /**
     * The application config.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $config;

    /**
     * Builder instance.
     *
     * @param \Illuminate\Support\Collection $query
     */
    public function __construct(Collection $query)
    {
        $this->query = $query;

        $this->config = collect(Config::get('restql', []));
    }

    /**
     * Static class instance.
     *
     * @param \Illuminate\Support\Collection $query
     * @return \Illuminate\Support\Collection
     */
    public static function make(Collection $query): Collection
    {
        return (new Builder($query))->dispatch();
    }

    /**
     * Chains the methods to the eloquent query.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function dispatch(): Collection
    {
        $this->query->each(function ($clausules, $modelKeyName) {
            /// Obtener el nombre de clase del modelo eloquent basado en
            /// los modelos permitidos para la resolución automatica
            /// de datos registrados en la configuración del usuario.
            $modelClassName = $this->getModelClassName($modelKeyName);

            if (class_exists($modelClassName)) {
                /// Determinar si la clase existe y esta sea una instancia
                /// de la clase \Illuminate\Database\Eloquent\Model.
                $model = app($modelClassName);
                if ($model instanceof Model) {
                    /// Ejecutar las clausulas recibidas por el cliente, solo
                    /// permitiendo las clausulas aceptadas por RestQL.
                    $builder = ClausuleExecutor::exec($model, collect($clausules)->only(
                        array_keys(ClausuleExecutor::ACCEPTED_CLAUSULES)
                    ));

                    /// Añadir a la respuesta un item que representa el nombre
                    /// del modelo como llave y una instancia de
                    /// \Illuminate\Database\Eloquent\Builder como valor.
                    $this->response[$modelKeyName] = $builder;
                }
            }
        });

        return collect($this->response);
    }

    /**
     * Get the model classname for the instance.
     *
     * @param  string $modelKeyName
     * @return string
     */
    protected function getModelClassName($modelKeyName): string
    {
        return $this->config->get('allowed_models', [])[$modelKeyName];
    }
}
