<?php

namespace Testing\Unit;

use PHPUnit\Framework\TestCase as FrameworkTestCase;
use Restql\Exceptions\InvalidSchemaDefinitionException;
use Restql\SchemaDefinition;

class SchemaDefinitionTest extends FrameworkTestCase
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

        $this->assertEquals($schema->getClass(), 'Testing\App\Author');
    }

    /**
     * @test Get the authorizer.
     */
    public function shemaReturnsTheCorrectAuthorizer(): void
    {
        $schema = self::genericDefinition();

        $this->assertEquals($schema->getAuthorizer(), 'Testing\App\Restql\Authorizers\AuthorAuthorizer');
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
    public function schemaDefinitionIsValid(): void
    {
        $schema = self::genericDefinition();

        $this->assertEquals('model', $schema->getType());

        $this->assertTrue($schema->imValid());
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
