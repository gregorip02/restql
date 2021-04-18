<?php

namespace Testing\Unit;

use Illuminate\Http\Request;
use Restql\RequestParser;
use Testing\TestCase;

class RequestParserTest extends TestCase
{
    /**
     * Fake request body.
     *
     * @var array
     */
    protected static $body = [
        'whoami' => true,
        'authors' => ['select' => 'name', 'unknow_clausule' => true],
        'unknow_schema_definition' => true
    ];

    /**
     * Create generic request.
     *
     * @return \Illuminate\Http\Request
     */
    protected static function genericRequest(): Request
    {
        return new Request([
            'query' => self::$body
        ]);
    }

    /**
     * @test Exclude unknow resources.
     */
    public function theParserExcludeUnknowResources(): void
    {
        $decoded = RequestParser::filter(self::genericRequest());
        $this->assertFalse($decoded->has('unknow_schema_definition'));
        $this->assertTrue($decoded->has('authors'));
    }
}
