<?php

namespace App\RestQL;

use App\RestQL\Builder;
use App\RestQL\Clausules\OrderByClausule;
use App\RestQL\Clausules\SelectClausule;
use App\RestQL\Clausules\WithClausule;

class RequestConstrains
{
    /**
     * Las clausulas aceptadas.
     *
     * @var array
     */
    public static $clausules = [
        'select' => SelectClausule::class,
        'orderBy' => OrderByClausule::class,
        'with' => WithClausule::class
    ];

    /**
     * The Eloquent Clausule.
     *
     * @var string
     */
    protected $clausule;

    /**
     * The Eloquent Clausule Constrains.
     *
     * @var array
     */
    protected $constrains;

    /**
     * La instancia de la clase.
     *
     * @param App\RestQL\Builder $builder
     * @param string $clausule
     * @param string|array $constrains
     */
    public function __construct(Builder $builder, string $clausule, $constrains)
    {
        $this->builder = $builder;
        $this->clausule = $clausule;
        $this->constrains = $constrains;
    }

    /**
     * Instancia estatica de la clase.
     *
     * @param App\RestQL\Builder $builder
     * @param string $clausule
     * @param array $constrains
     * @return string|array
     */
    public static function args(Builder $builder, string $clausule, $constrains)
    {
        return (new RequestConstrains(...func_get_args()))->get();
    }

    /**
     * Obtener los argumentos de la clausula.
     *
     * @return string|array
     */
    public function get()
    {
        return app($this->clausuleContractClass())->args(
            ...$this->getClausuleContractArgs()
        );
    }

    /**
     * La clase que retorna los argumentos parseados de la clausula.
     *
     * @return string
     */
    protected function clausuleContractClass(): string
    {
        return $this::$clausules[$this->clausule];
    }

    /**
     * Obtiene los argumentos que deben recibir todas las clausulas por contrato.
     *
     * @return array
     */
    protected function getClausuleContractArgs(): array
    {
        return [$this->builder, $this->constrains];
    }
}
