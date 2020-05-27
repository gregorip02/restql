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
        $argument = new Argument($this->explicitValues);

        $this->assertTrue($argument->isAssoc());
    }

    /**
     * @test Check the isAssoc function returns false.
     */
    public function theArgumentValuesAreNotNotAssoc()
    {
        $argument = new Argument($this->implicitValues);

        $this->assertFalse($argument->isAssoc());
    }

    /**
     * @test Check the isImplicit method in Argument class.
     */
    public function theArgumentValueAreSimpleAndImplicit()
    {
        $argument = new Argument(['id']);

        $this->assertTrue($argument->isImplicitValue());

        $this->assertFalse($argument->isAssoc());
    }

    /**
     * @test Check the argument returns unchanged values.
     */
    public function theArgumentReturnsUnchangedValuesWithExplicitValues()
    {
        $data = (new Argument($this->explicitValues))->data();

        $this->assertEquals($this->explicitValues, $data);
    }

    /**
     * @test Check the argument returns unchanged values.
     */
    public function theArgumentReturnsUnchangedValuesWithImplicitValues()
    {
        $data = (new Argument($this->implicitValues))->data();

        $this->assertEquals($this->implicitValues, $data);
    }
}
