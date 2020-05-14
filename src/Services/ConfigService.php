<?php

namespace Restql\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Restql\Clausule;
use Restql\ClausuleExecutor;
use Restql\Exceptions\InvalidSchemaDefinitionException;

final class ConfigService
{
    /**
     * The package config collection.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $config;

    /**
     * Create class instance.
     */
    public function __construct()
    {
        $config = Config::get('restql', []);

        $this->config = Collection::make($config);
    }

    /**
     * Get an item from the collection by key.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    /**
     * Get schema definition.
     *
     * @return array
     */
    public function getSchema(): array
    {
        return $this->get('schema', []);
    }

    /**
     * Get allowed resolvers.
     *
     * @return array
     */
    public function getResolvers(): array
    {
        return $this->get('resolvers', []);
    }

    /**
     * Get the allowed clausules.
     *
     * @return array
     */
    public function getClausules(): array
    {
        return $this->get('clausules', []);
    }

    /**
     * Determine if a key or className is registered in the config.
     *
     * @param  string  $keyOrClassName
     * @return boolean
     */
    public function hasClausule(string $keyOrClassName): bool
    {
        $clausules = $this->getClausules();

        return in_array($keyOrClassName, $clausules) || array_key_exists($keyOrClassName, $clausules);
    }

    /**
     * Create a instance of Clausule based on the key name.
     *
     * @param  string $keyOrClassName
     * @param  array  $arguments
     * @return \Restql\Clausule
     */
    public function createClasuleInstance(ClausuleExecutor $executor, string $keyName, array $arguments): Clausule
    {
        $classname = $this->getClausules()[$keyName];

        if (! class_exists($classname)) {
            // Exception here.
        }

        return new $classname($executor, Collection::make($arguments));
    }

    /**
     * Merge the schema and the resolver definition.
     *
     * @return array
     */
    public function getFullSchema(): array
    {
        return array_merge($this->getResolvers(), $this->getSchema());
    }

    /**
     * Determine if the key exists in full schema definition.
     *
     * @param  string $keyName
     * @return bool
     */
    public function inSchema(string $keyName): bool
    {
        return in_array($keyName, $this->getFullSchemaKeyNames(), true);
    }

    /**
     * Get schema definition or fail.
     *
     * @param  string $keyName
     * @return array
     */
    public function getSchemaDefinitionOrFail(string $keyName): array
    {
        if (! $this->inSchema($keyName)) {
            throw new InvalidSchemaDefinitionException(
                $keyName,
                sprintf('The key name [%s] is not defined.', $keyName)
            );
        }

        return $this->getSchemaDefinition($keyName);
    }

    /**
     * Get schema definition.
     *
     * @param  string $keyName
     * @return array
     */
    public function getSchemaDefinition(string $keyName): array
    {
        return $this->getFullSchema()[$keyName];
    }

    /**
     * Get the schema keys names.
     *
     * @return array
     */
    public function getSchemaKeys(): array
    {
        return array_keys($this->getSchema());
    }

    /**
     * Get the resolvers keys names.
     *
     * @return array
     */
    public function getResolversKey(): array
    {
        return array_keys($this->getResolvers());
    }

    /**
     * Get the allowed key names merging the schema keys into resolvers key names.
     *
     * @return array
     */
    public function getFullSchemaKeyNames(): array
    {
        return array_keys($this->getFullSchema());
    }
}
