<?php

namespace App\RestQL;

use Illuminate\Http\Request;
use App\RestQL\RequestConstrains;
use Illuminate\Support\Collection;

class RequestParser
{
    /**
     * La petición entrante.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * El nombre del paremetro que sera evaluado en la petición.
     *
     * @var string
     */
    protected $param;

    /**
     * Instancia de la clase.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     */
    public function __construct(Request $request, string $param)
    {
        $this->request = $request;
        $this->param = $param;
    }

    /**
     * Instancia estatica de la clase.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $param
     * @return \Illuminate\Support\Collection
     */
    public static function filter(Request $request, string $param): Collection
    {
        return (new RequestParser(...func_get_args()))->parse();
    }

    /**
     * Decodifica el parametro y devuelve una colección con el.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function parse(): Collection
    {
        return $this->hasParam() ? $this->decodeParam() : collect(
            $this->request->only(
                $this->acceptedClausules()
            )
        );
    }

    /**
     * Determina si se envio el parametro en la petición.
     *
     * @return boolean
     */
    protected function hasParam(): bool
    {
        return $this->request->has($this->param);
    }

    /**
     * Obtiene el parametro en la petición.
     *
     * @return string
     */
    protected function getParam(): string
    {
        return $this->request->get($this->param) ?? '';
    }

    /**
     * Decodifica el parametro recibido en la petición.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function decodeParam(): Collection
    {
        $param = $this->getParam();

        return collect(json_decode(base64_decode($param)))->only(
            $this->acceptedClausules()
        );
    }

    /**
     * Los nombres de las clausulas aceptadas.
     *
     * @return array
     */
    protected function acceptedClausules(): array
    {
        return array_keys(RequestConstrains::$clausules);
    }
}
