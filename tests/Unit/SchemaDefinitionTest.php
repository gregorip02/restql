<?php

namespace Testing\Unit;

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
        return new SchemaDefinition('authors', [
            'class' => 'App\Author'
        ]);
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
     * @test Get the arguments.
     */
    public function schemaReturnsTheCorrectArguments(): void
    {
        $schema = self::genericDefinition();

        $this->assertEquals($schema->getArguments(), [
            'class' => 'App\Author'
        ]);
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
}
