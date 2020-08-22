<?php

namespace Testing\Unit;

use Testing\TestCase;

final class RouteProviderTest extends TestCase
{
    /**
     * @test Accede a la ruta expuesta para testing.
     */
    public function testRouteForTestingIsPublished(): void
    {
        $response = $this->get('/restql');
        $response->assertOk();
    }
}
