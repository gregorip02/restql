<?php

namespace Restql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Restql\Authorizer;
use Restql\Exceptions\InvalidSchemaDefinitionException;
use Restql\Resolver;
use Restql\Resolvers\QueryBuilderResolver;
use Restql\Services\ConfigService;

final class SchemaDefinition
{
    /**
     * Definition key name.
     *
     * @var string
     */
    protected $keyName;

    /**
     * The schema definition.
     *
     * @var array
     */
    protected $schema;

    /**
     * Schema incoming body arguments.
     *
     * @var array
     */
    protected $arguments;

    /**
     * Class instance.
     *
     * @param string $keyName
     * @param array  $arguments
     */
    public function __construct(string $keyName, array $arguments = [])
    {
        $this->keyName = $keyName;

        $this->arguments = $arguments;

        $this->schema = app(ConfigService::class)->getSchemaDefinitionOrFail($keyName);
    }

    /**
     * Get the schema definition key name.
     *
     * @return string
     */
    public function getKeyName(): string
    {
        return $this->keyName;
    }

    /**
     * Get schema definition class.
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->getAttributeOrFail('class');
    }

    /**
     * Get the schema definition arguments.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Return a collection of schema definition arguments.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCollectionArguments(): Collection
    {
        $arguments = $this->arguments;

        return Collection::make($arguments);
    }

    /**
     * Return the schema definition middlewares.
     *
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->schema['middlewares'] ?? [];
    }

    /**
     * Get the authorizer class name.
     *
     * @return string
     */
    public function getAuthorizer(): string
    {
        return $this->getAttribute('authorizer', Authorizer::class);
    }

    /**
     * Get the authorizer instance.
     *
     * @return \Restql\Authorizer
     */
    public function getAuthorizerInstance(): Authorizer
    {
        $authorizer = $this->getAuthorizer();

        return new $authorizer();
    }

    /**
     * Get schema definition type.
     *
     * @return string
     */
    public function getType(): string
    {
        $classname = $this->getClass();

        $classname = class_exists($classname) ? class_parents($classname) : [$classname];

        $parentClass = Arr::last($classname);

        return Str::lower(class_basename($parentClass));
    }

    /**
     * Determine if the schema definition is valid.
     *
     * @return bool
     */
    public function imValid(): bool
    {
        return class_exists($this->getClass()) && in_array($this->getType(), [
            'model', 'resolver'
        ]);
    }

    /**
     * Create resolver instance.
     *
     * @return Restql\Resolver
     */
    public function createInstance(): Resolver
    {
        if ($this->getType() === 'model') {
            /// TODO: Document this.
            $classname = QueryBuilderResolver::class;
        } else {
            /// TODO: Document this
            $classname = $this->getClass();
        }

        return new $classname();
    }

    /**
     * Handle the resolver or model.
     *
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
        return ($this->createInstance())->handle($this);
    }

    /**
     * Get the schema attribute.
     *
     * @param  string $attribute
     * @param  string $default
     *
     * @return string
     */
    protected function getAttribute(string $attribute, string $default = ''): string
    {
        return $this->schema[$attribute] ?? $default;
    }

    /**
     * Get schema attribute definition or fail.
     *
     * @param  string $attribute
     * @return string
     */
    protected function getAttributeOrFail(string $attribute): string
    {
        return $this->getAttribute($attribute) ?? new InvalidSchemaDefinitionException(
            $this->keyName,
            sprintf('The attribute [%s] is not defined.', $attribute)
        );
    }
}
