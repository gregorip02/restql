<?php

namespace Testing\Feature\Clausules;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Testing\App\Article;
use Testing\App\Author;
use Testing\App\Comment;
use Testing\TestCase;

final class TakeClausuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test Take 5 resources only.
     */
    public function takeFiveResourcesOnly(): void
    {
        Article::factory(6)->create();

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
        Comment::factory(11)->create();

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
        Comment::factory(6)->create();
        Article::factory(7)->create();
        Author::factory(16)->create();

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
