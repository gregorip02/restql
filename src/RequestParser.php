<?php

namespace Restql;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RequestParser
{
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
        return $this->hasParam() ? $this->decodeParam() : collect(
            $this->request->only($this->allowesModels())
        );
    }

    /**
     * Determine if the parameter was sent in the request.
     *
     * @return boolean
     */
    protected function hasParam(): bool
    {
        /// TODO: get this value from a config file.
        return $this->request->has('query');
    }

    /**
     * Get the request param value.
     *
     * @return string
     */
    protected function getParam(): string
    {
        /// TODO: get this value from a config file.
        return (string) collect($this->request->get('query'));
    }

    /**
     * Decode the parameter.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function decodeParam(): Collection
    {
        return collect(json_decode(base64_decode($this->getParam())))->only(
            $this->allowesModels()
        );
    }

    /**
     * The accepted clauses names.
     *
     * @return array
     */
    protected function allowesModels(): array
    {
        /// TODO: get this value from a config file.
        return array_keys([
            'authors' => 'App\Author',
            'articles' => 'App\Article',
            'comments' => 'App\Comment'
        ]);
    }
}
