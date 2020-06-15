<?php

namespace Testing\Feature;

use Testing\TestCase;

final class SelectClausuleTest extends TestCase
{
  /**
   * @test Gets empty response.
   */
  public function getsEmptyResponse(): void
  {
    $response = $this->json('get', 'restql');

    $response->assertJson([
      'data' => []
    ]);
  }
}
