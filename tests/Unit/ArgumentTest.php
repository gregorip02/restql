<?php

namespace Testing\Unit;

use Illuminate\Support\Collection;
use Restql\Argument;
use Testing\TestCase;

class ArgumentTest extends TestCase
{
    /**
     * Fake argument explicit values.
     *
     * @var array
     */
    private $explicitValues = [
        'value'  => 100,
        'column' => 'id'
    ];

    /**
     * Fake argument implicit values.
     *
     * @var array
     */
    private $implicitValues = ['id', 100];

    /**
     * @test Check the isAssoc function returns true.
     */
    public function theArgumentValuesAreAssoc()
    {
        $values = collect($this->explicitValues);

        $argument = new Argument($values);

        $this->assertTrue($argument->isAssoc());
    }

    /**
     * @test Check the isAssoc function returns false.
     */
    public function theArgumentValuesAreNotNotAssoc()
    {
        $values = collect($this->implicitValues);

        $argument = new Argument($values);

        $this->assertFalse($argument->isAssoc());

        $argument = new Argument(collect([]));

        $this->assertFalse($argument->isAssoc());
    }

    /**
     * @test Check the isImplicit method in Argument class.
     */
    public function theArgumentValueAreSimpleAndImplicit()
    {
        $values = collect('id');

        $argument = new Argument($values);

        $this->assertTrue($argument->isImplicitValue());

        $this->assertFalse($argument->isAssoc());
    }

    /**
     * @test Check the argument returns unchanged values.
     */
    public function theArgumentReturnsUnchangedValuesWithExplicitValues()
    {
        $values = collect($this->explicitValues);

        $data = (new Argument($values))->data();

        $this->assertEquals($values->toArray(), $data);
    }

    /**
     * @test Check the argument returns unchanged values.
     */
    public function theArgumentReturnsUnchangedValuesWithImplicitValues()
    {
        $values = collect($this->implicitValues);

        $data = (new Argument($values))->data();

        $this->assertEquals($values->toArray(), $data);
    }
}
