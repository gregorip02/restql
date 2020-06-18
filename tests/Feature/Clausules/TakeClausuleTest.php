<?php

namespace Testing\Feature\Clausules;

use Testing\TestCase;

final class TakeClausuleTest extends TestCase
{
    /**
     * @test Take 5 resources only.
     */
    public function takeFiveResourcesOnly(): void
    {
        $response = $this->json('get', 'restql', [
            'articles' => [
                'take' => 5
            ]
        ]);

        $response->assertJsonCount(5, 'data.articles');
    }

    /**
     * @test Take 5 resources only.
     */
    public function takeTenResourcesOnlyUsingArrayMethod(): void
    {
        $response = $this->json('get', 'restql', [
            'comments' => [
                'take' => [10, 11, 12]
            ]
        ]);

        $response->assertJsonCount(10, 'data.comments');
    }

    /**
     * @test Take specific numbers of resources from diferent models.
     */
    public function takeSpecificResourcesFromDiferentModels(): void
    {
        $response = $this->json('get', 'restql', [
            'comments' => ['take' => 5],
            'articles' => ['take' => 6],
            'authors' => true
        ]);

        $response->assertJsonCount(5, 'data.comments');
        $response->assertJsonCount(6, 'data.articles');
        $response->assertJsonCount(15, 'data.authors');
    }
}
