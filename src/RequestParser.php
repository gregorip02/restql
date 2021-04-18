<?php

namespace Restql;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        $resources = $this->hasParam() ? $this->getQueryParamValue() : $this->request->all();

        return $this->getAllowedResources($resources);
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
     * @return array
     */
    protected function getQueryParamValue(): array
    {
        $paramName = $this->getQueryParamName();

        $paramValue = $this->request->input($paramName);

        if (!is_array($paramValue)) {
            throw new Exception(sprintf('The value of param %s must be array', $paramName));
        }

        return $paramValue;
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
