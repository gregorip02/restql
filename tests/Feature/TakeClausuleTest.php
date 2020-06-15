<?php

namespace Testing\Feature;

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
}
