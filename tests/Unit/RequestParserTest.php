<?php

namespace Tests\Unit;

use Tests\TestCase;
use Restql\RequestParser;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RequestParserTest extends TestCase
{
    /**
     * Fake client query.
     *
     * @var array
     */
    private $query = [
        'authors' => true,
        'unallowed_model' => true
    ];

    /**
     * @test Exclude unknow key models from the query.
     */
    public function theParserExcludeNotAllowedModels(): void
    {
        $request = new Request($this->query);

        $filter = RequestParser::filter($request);

        $this->assertTrue($filter->has('authors'));

        $this->assertFalse($filter->has('unallowed_model'));
    }

    /**
     * @test Exclude unknow key models from base64 encoding query.
     */
    public function theParserExcludeNotAllowedModelsWithBase64Query(): void
    {
        $request = new Request([
            'query' => base64_encode(json_encode($this->query))
        ]);

        $filter = RequestParser::filter($request);

        $this->assertTrue($filter->has('authors'));

        $this->assertFalse($filter->has('unallowed_model'));
    }
}
