<?php

namespace Testing\Unit;

use PHPUnit\Framework\TestCase as FrameworkTestCase;

final class ConfigTest extends FrameworkTestCase
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
