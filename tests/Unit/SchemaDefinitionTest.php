<?php

namespace Testing\Unit;

use Restql\Authorizer;
use Restql\Exceptions\InvalidSchemaDefinitionException;
use Restql\SchemaDefinition;
use Testing\TestCase;

class SchemaDefinitionTest extends TestCase
{
    /**
     * Fake schema generator.
     *
     * @return \Restql\SchemaDefinition
     */
    protected static function genericDefinition(): SchemaDefinition
    {
        return new SchemaDefinition('authors', []);
    }

    /**
     * @test Get the key name.
     */
    public function schemaReturnsTheCorrectKeyName(): void
    {
        $schema = self::genericDefinition();

        $this->assertEquals('authors', $schema->getKeyName());
    }

    /**
     * @test Get the class named.
     */
    public function schemaReturnsTheCorrectClassName(): void
    {
        $schema = self::genericDefinition();

        $this->assertEquals($schema->getClass(), 'App\Author');
    }

    /**
     * @test Get the authorizer.
     */
    public function shemaReturnsTheCorrectAuthorizer(): void
    {
        $schema = self::genericDefinition();

        $this->assertEquals($schema->getAuthorizer(), 'App\Restql\Authorizers\AuthorAuthorizer');
    }

    /**
     * @test Get the arguments.
     */
    public function schemaReturnsTheCorrectClausules(): void
    {
        $schema = self::genericDefinition();

        $this->assertEquals($schema->getClausules(), []);
    }

    /**
     * @test It's not valid.
     */
    public function schemaDefinitionItsNotValid(): void
    {
        $schema = self::genericDefinition();

        $this->assertEquals('author', $schema->getType());

        // it's not valid because the App\Author class doesn't exists.
        $this->assertFalse($schema->imValid());
    }

    /**
     * @test Invalid schema definition exception.
     */
    public function schemaDefinitionThrowsInvalidSchemaDefinition(): void
    {
        $this->expectException(InvalidSchemaDefinitionException::class);

        $this->assertInstanceOf(InvalidSchemaDefinitionException::class, new SchemaDefinition('bad-key-name', []));
    }
}
