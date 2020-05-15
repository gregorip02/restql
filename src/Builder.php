<?php

namespace Restql;

use Illuminate\Support\Collection;
use Restql\SchemaExecutor;
use Restql\Services\ConfigService;
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
     * Builder instance.
     *
     * @param \Illuminate\Support\Collection $query
     */
    public function __construct(Collection $query)
    {
        $this->query = $query;
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
        $this->schema()->each(function (SchemaDefinition $schema) {
            /// Executing the "handle" method in the schema definition, this will
            /// return a collection with data resolved independently.
            $this->response[$schema->getKeyName()] = $schema->handle();
        });

        return Collection::make($this->response);
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
