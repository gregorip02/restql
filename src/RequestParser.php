<?php

namespace Restql;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Restql\Exceptions\InvalidEncodingValue;

class RequestParser
{
    /**
     * The HTTP incoming request.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The application config.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $config;

    /**
     * Class instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->config = collect(Config::get('restql', []));
    }

    /**
     * Static class instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public static function filter(Request $request): Collection
    {
        return (new RequestParser($request))->decode();
    }

    /**
     * Decode the parameter and return a collection with it.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function decode(): Collection
    {
        if (strlen($this->getQueryParamName()) && $this->hasParam()) {
            /// Si el usuario decide darle un valor al atributo en la
            /// configuración "query_param", entonces se asumirá que
            /// la aplicación acepta consultas codificadas en base64
            /// enviadas como parámetros en la petición HTTP.

            /// De igual manera, cuando esto sucede, el valor de la
            /// consulta debe estar obligatoria-mente codificada en
            /// base64.
            return $this->decodeParam();
        }

        /// Por defecto, restql acepta consultas que vienen en el cuerpo
        /// de la petición HTTP y no están codificadas en base64.
        return collect(
            $this->request->only($this->allowedModels())
        );
    }

    /**
     * Determine if the parameter was sent in the request.
     *
     * @return boolean
     */
    protected function hasParam(): bool
    {
        return $this->request->has(
            $this->getQueryParamName()
        );
    }

    /**
     * Get the request param value.
     *
     * @return string
     */
    protected function getQueryParamValue(): string
    {
        return (string) $this->request->get(
            $this->getQueryParamName()
        );
    }

    /**
     * Get the query param name defined in the configuration.
     *
     * @return string
     */
    protected function getQueryParamName(): string
    {
        return $this->config->get('query_param', '');
    }

    /**
     * Decode the parameter.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function decodeParam(): Collection
    {
        /// Get the client query value.
        $queryValue = $this->getQueryParamValue();

        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $queryValue)) {
            /// Cuando el cliente envia valores en los parametros
            /// de la petición, deben estar codificados en base64.
            throw new InvalidEncodingValue($this->getQueryParamName());
        }

        return collect(json_decode(base64_decode($queryValue)))->only(
            $this->allowedModels()
        );
    }

    /**
     * The accepted clauses names.
     *
     * @return array
     */
    protected function allowedModels(): array
    {
        return array_keys($this->config->get('allowed_models', []));
    }
}
