<?php

namespace Restql;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Restql\Exceptions\InvalidEncodingValue;
use Restql\Services\ConfigService;
use Restql\Traits\HasConfigService;

final class RequestParser
{
    use HasConfigService;

    /**
     * The HTTP incoming request.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Class instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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
        return $this->getAllowedResources($this->request->all());
    }

    /**
     * Determine if the parameter was sent in the request.
     *
     * @return boolean
     */
    protected function hasParam(): bool
    {
        $paramName = $this->getQueryParamName();

        return $this->request->has($paramName);
    }

    /**
     * Get the request param value.
     *
     * @return string
     */
    protected function getQueryParamValue(): string
    {
        $paramName = $this->getQueryParamName();

        return $this->request->get($paramName);
    }

    /**
     * Get the query param name defined in the configuration.
     *
     * @return string
     */
    protected function getQueryParamName(): string
    {
        return $this->getConfigService()->get('query_param', '');
    }

    /**
     * Decode the parameter.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function decodeParam(): Collection
    {
        $query = json_decode(base64_decode($this->getQueryParamValue()));

        return $this->getAllowedResources($query);
    }

    /**
     * The accepted resources key names (models/resolvers).
     *
     * @return array
     */
    public function getAllowedResources(array $array): Collection
    {
        $keyNames = $this->getConfigService()->getFullSchemaKeyNames();

        return collect($array)->only($keyNames);
    }
}
