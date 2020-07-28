<?php

namespace Restql;

use Closure;
use Restql\Builder;
use Restql\RequestParser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Responsable;

final class Restql implements Responsable
{
    /**
     * The collection response.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $response;

    /**
     * The incoming HTTP Request.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Restql instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Start data resolution from the eloquent models.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Restql\Restql
     */
    public static function resolve(Request $request): Restql
    {
        return (new Restql($request))->build();
    }

    /**
     * Start data resolution from the eloquent models with array.
     *
     * @param  array  $array
     * @return \Restql\Restql
     */
    public static function resolveWithArr(array $array = []): Restql
    {
        return self::resolve(new Request($array));
    }

    /**
     * Dispatch the builder make method and save the response.
     *
     * @return \Restql\Restql
     */
    protected function build(): Restql
    {
        $filter = RequestParser::filter($this->request);

        $this->response = Builder::make($filter);

        return $this;
    }

    /**
     * Create a new Eloquent Collection instance by default.
     *
     * @param  Closure $callback
     * @return Collection
     */
    public function get(Closure $callback = null): Collection
    {
        return $this->response->map(function (Collection $data) use ($callback) {
            /// You can pass a clousure with the eloquente query
            /// builder has argument. This allow you to add and
            /// resolve the data based on your logic.
            if ($callback instanceof Closure) {
                return $callback($data);
            }

            return $data;
        });
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return new Response([
            'data' => $this->get()
        ]);
    }
}
