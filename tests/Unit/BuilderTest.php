<?php

namespace Testing\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Restql\Builder;
use Restql\Exceptions\InvalidSchemaDefinitionException;
use Restql\RequestParser;
use Testing\TestCase;

class BuilderTest extends TestCase
{
    /**
     * Generate fake request.
     *
     * @return \Illuminate\Http\Request
     */
    protected static function generateFakeRequest(): Request
    {
        return new Request(['fake' => true, 'author' => false]);
    }

    /**
     * @test Test the schema filter method.
     */
    public function theBuilderMakeAValidSchema(): void
    {
        $request = self::generateFakeRequest();

        $query = RequestParser::filter($request);

        $schema = (new Builder($query))->schema();

        $this->assertEquals($schema, $query);
    }

    /**
     * @test Fails when send unparsed query.
     */
    public function theBuidlerThrowsInvalidSchemaDefinitionWithUnparsedQuery(): void
    {
        $this->expectException(InvalidSchemaDefinitionException::class);

        $request = self::generateFakeRequest()->all();

        $schema = (new Builder(Collection::make($request)))->schema();
    }
}
