<?php

namespace Restql;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

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
        return $this->hasParam() ? $this->decodeParam() : collect(
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
        return $this->config->get('query_param', 'query');
    }

    /**
     * Decode the parameter.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function decodeParam(): Collection
    {
        /// Get the client query value.
        $query = $this->getQueryParamValue();

        /// Get the client query param name.
        $name = $this->getQueryParamName();

        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $query)) {
            /// Cuando el cliente envia valores en los parametros
            /// de la petición, deben estar codificados en base64.
            throw new \Exception('The param \'$name\' isn\'t a base64 string encoded', 1);
        }

        $value = json_decode(base64_decode($query));

        if (!is_array($value)) {
            /// Los datos decodificados en base64 deben tener un
            /// valor de tipo array.
            throw new \Exception('The param \'$name\' decoded isn\'t a array', 1);
        }

        return collect($value)->only(
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
