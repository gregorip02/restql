<?php

namespace Restql;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Restql\ClausuleExecutor;

class RequestParser
{
    /**
     * The HTTP incoming request.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The parameter name that will be evaluated in the request.
     *
     * @var string
     */
    protected $param;

    /**
     * Class instance.
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
     * Static class instance and parser.
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
     * Decode the parameter and return a collection with it.
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
     * Determine if the parameter was sent in the request.
     *
     * @return boolean
     */
    protected function hasParam(): bool
    {
        return $this->request->has($this->param);
    }

    /**
     * Get the request param value.
     *
     * @return string
     */
    protected function getParam(): string
    {
        return $this->request->get($this->param) ?? '';
    }

    /**
     * Decode the parameter.
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
     * The accepted clauses names.
     *
     * @return array
     */
    protected function acceptedClausules(): array
    {
        return array_keys(ClausuleExecutor::$accepted);
    }
}
