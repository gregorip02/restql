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
     * Create generic encoded request.
     *
     * @return \Illuminate\Http\Request
     */
    protected static function genericEncodedRequest(): Request
    {
        $body = base64_encode(json_encode(self::$body));

        return new Request([
            'query' => $body
        ]);
    }

    /**
     * Create generic request.
     *
     * @return \Illuminate\Http\Request
     */
    protected static function genericRequest(): Request
    {
        return new Request(self::$body);
    }

    /**
     * @test Exclude unknow resources.
     */
    public function theParserExcludeUnknowResources(): void
    {
        $encoded = RequestParser::filter(self::genericEncodedRequest());

        $decoded = RequestParser::filter(self::genericRequest());

        $this->assertFalse($encoded->has('unknow_schema_definition'));
        $this->assertFalse($decoded->has('unknow_schema_definition'));

        $this->assertTrue($encoded->has('authors'));
        $this->assertTrue($decoded->has('authors'));
    }
}
