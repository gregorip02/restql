<?php

namespace Tests\Unit;

use Illuminate\Support\Collection;
use Restql\Builder;
use Tests\TestCase;

class BuilderTest extends TestCase
{
    /**
     * Fake incoming query values.
     *
     * @var array
     */
    private $query = [
        'authors' => ['select' => 'name', 'sort' => true, 'fake' => 'fake', 'another_fake' => []],
        'unknows' => ['select' => 'unknow_field']
    ];

    /**
     * @test Check if the "fake" clausule is removed from the builder.
     */
    public function theBuilderExcludeUnknowClausules()
    {
        $builder = new Builder(collect($this->query));

        $filter = $builder->filterClausules($this->query['authors']);

        $this->assertTrue($filter->has('select'));

        $this->assertTrue($filter->has('sort'));

        $this->assertFalse($filter->has('fake'));

        $this->assertEquals($filter, collect($this->query['authors'])->forget([
            'fake', 'another_fake'
        ]));
    }

    /**
     * @test Check if the "unknows" key name is removed from the
     */
    public function theBuilderExcludeUnknowModelKeyNames()
    {
        $builder = new Builder(collect($this->query));

        $filter = $builder->getAllowedModels();

        $this->assertTrue($filter->has('authors'));

        $this->assertFalse($filter->has('unknows'));

        $this->assertEquals($filter, collect($this->query)->forget('unknows'));
    }
}
