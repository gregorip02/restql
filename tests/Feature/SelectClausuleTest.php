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

  /**
   * @test Select specific attributes using implicit method
   */
  public function getSpecificAttributesUsingImplicitMethod(): void
  {
    $response = $this->json('get', 'restql', [
      'authors' => [
        'select' => 'name'
      ]
    ]);

    $response->assertJsonCount(15, 'data.authors');

    $response->assertJsonStructure([
      'data' => ['authors' => [['name', 'id']]]
    ]);
  }

    /**
   * @test Select specific attributes using explicit method
   */
  public function getSpecificAttributesUsingExplicitMethod(): void
  {
    $response = $this->json('get', 'restql', [
      'authors' => [
        'select' => ['name']
      ]
    ]);

    $response->assertJsonCount(15, 'data.authors');

    $response->assertJsonStructure([
      'data' => ['authors' => [['name', 'id']]]
    ]);
  }
}
