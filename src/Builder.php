<?php

namespace Restql;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Restql\Exceptions\AccessDeniedHttpException;
use Restql\SchemaDefinition;
use Restql\Traits\HasConfigService;

final class Builder
{
    use HasConfigService;

    /**
     * A query collection of models.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $query;

    /**
     * The array response.
     *
     * @var array
     */
    protected $response = [];

    /**
     * The application defined middleware short-hand names.
     *
     * @var array
     */
    protected $routeMiddleware = [];

    /**
     * Builder instance.
     *
     * @param \Illuminate\Support\Collection $query
     */
    public function __construct(Collection $query)
    {
        $this->query = $query;

        $this->routeMiddleware = app('router')->getMiddleware();
    }

    /**
     * Static class instance.
     *
     * @param \Illuminate\Support\Collection $query
     * @return \Illuminate\Support\Collection
     */
    public static function make(Collection $query): Collection
    {
        return (new Builder($query))->dispatch();
    }

    /**
     * Chains the methods to the eloquent query.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function dispatch(): Collection
    {
        $schema = $this->schema();

        $this->checkMiddlewares($schema);

        $this->checkAuthorizers($schema);

        $schema->each(function (SchemaDefinition $schema) {
            /// Executing the "handle" method in the schema definition, this will
            /// return a collection with data resolved independently.
            $this->response[$schema->getKeyName()] = $schema->handle();
        });

        return Collection::make($this->response);
    }

    /**
     * Checks middlewares for incoming request.
     *
     * @param  \Illuminate\Support\Collection $schema
     * @return void
     */
    protected function checkAuthorizers(Collection $schema): void
    {
        $method = Str::lower(request()->method());

        $schema->each(function (SchemaDefinition $schema) use ($method) {
            $instance = $schema->getAuthorizerInstance();

            $clausules = $schema->getClausules();

            if (! call_user_func([$instance, $method], $clausules)) {
                throw new AccessDeniedHttpException(
                    $schema->getKeyName(),
                    $method
                );
            }
        });
    }

    /**
     * Checks middlewares for incoming request.
     *
     * @param  \Illuminate\Support\Collection $schema
     * @return void
     */
    protected function checkMiddlewares(Collection $schema): void
    {
        $middlewares = $this->getMiddlewareClasses($schema);

        $request = app('request');

        app(Pipeline::class)->send($request)->through($middlewares)->thenReturn();
    }

    /**
     * Create an array of middlewares classess.
     *
     * @param  \Illuminate\Support\Collection $schema
     * @return array
     */
    protected function getMiddlewareClasses(Collection $schema): array
    {
        return $schema->reduce(
            function (array $reducer, SchemaDefinition $schemaDefinition) {
                foreach ($schemaDefinition->getMiddlewares() as $key => $value) {
                    $middlewareClass = $this->routeMiddleware[$value] ?? false;
                    if ($middlewareClass && !in_array($middlewareClass, $reducer)) {
                        $reducer[] = $middlewareClass;
                    }
                }

                return $reducer;
            },
            []
        );
    }

    /**
     * Remove unknow model key and resolvers names from the incoming query.
     *
     * @return \Illuminate\Support\Collection
     */
    public function schema(): Collection
    {
        return $this->query->map(function ($arguments, $schemaKeyName) {
            /// Create an SchemaDefinition instance.
            return new SchemaDefinition($schemaKeyName, (array) $arguments);
        })->filter(function (SchemaDefinition $schema) {
            /// Checks if the schema class exists and be a
            /// 'Illuminate\Database\Eloquent\Model' or 'Restql\Resolver' children.
            return $schema->imValid();
        });
    }
}
