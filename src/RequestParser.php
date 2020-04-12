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
            /// If the user decides to give a value to the attribute in the
            /// "query_param" setting, then it will be assumed that
            /// the application accepts base64 encoded queries
            /// sent as parameters in the HTTP request.

            /// Similarly, when this happens, the value of the
            /// query must be compulsorily encoded in
            /// base64.
            return $this->decodeParam();
        }

        /// By default, restql accepts queries that come in the body
        /// of the HTTP request and are not base64 encoded.
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

        /// When the client sends values in the parameters
        /// of the request, must be base64 encoded.
        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $queryValue)) {
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
