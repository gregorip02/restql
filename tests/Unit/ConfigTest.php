<?php

namespace Testing\Unit;

use Testing\TestCase;

final class ConfigTest extends TestCase
{
    /**
     * @test Testing clausules are published correctly.
     */
    public function assertCountClausulesBetweenTestingAndPublished(): void
    {
        $internal = require(__DIR__ . '/../App/config/restql.php');

        $external = require(__DIR__ . '/../../config/restql.php');

        $this->assertEquals(count($internal['clausules']), count($external['clausules']));
    }
}
