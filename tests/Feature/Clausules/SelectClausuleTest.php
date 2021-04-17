<?php

namespace Testing\Feature\Clausules;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Testing\App\Article;
use Testing\App\Author;
use Testing\App\Comment;
use Testing\TestCase;

final class SelectClausuleTest extends TestCase
{
    use RefreshDatabase;

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
        Author::factory($count = random_int(5, 15))->create();

        $response = $this->json('get', 'restql', [
            'authors' => [
                'select' => 'name'
            ]
        ]);

        $response->assertJsonCount($count, 'data.authors');

        $response->assertJsonStructure([
            'data' => ['authors' => [['name', 'id']]]
        ]);
    }

    /**
    * @test Select specific attributes using explicit method
    */
    public function getSpecificAttributesUsingExplicitMethod(): void
    {
        Author::factory($count = rand(1, 15))->create();

        $response = $this->json('get', 'restql', [
            'authors' => [
                'select' => ['name']
            ]
        ]);

        $response->assertJsonCount($count, 'data.authors');
        $response->assertJsonStructure([
            'data' => ['authors' => [['name', 'id']]]
        ]);
    }

    /**
    * @test Get specific attributes from diferents models.
    */
    public function getSpecificAttributesFromDiferentsModels(): void
    {
        Author::factory($authorsCount = rand(1, 10))->create();
        Article::factory($articlesCount = rand(2, 10))->create();
        Comment::factory($commentsCount = rand(1, 10))->create();

        $response = $this->json('get', 'restql', [
            'authors' => [
                'select' => ['name', 'email'],
                'take' => $authorsCount
            ],
            'articles' => [
                'select' => 'title',
                'take' => $articlesCount - 1
            ],
            'comments' => [
                'select' => ['content'],
                'take' => $commentsCount
            ]
        ]);

        $response->assertJsonCount(3, 'data');
        $response->assertJsonCount($authorsCount, 'data.authors');
        $response->assertJsonCount($articlesCount - 1, 'data.articles');
        $response->assertJsonCount($commentsCount, 'data.comments');

        $response->assertJsonStructure([
            'data' => [
                'authors' => [['id', 'name', 'email']],
                'articles' => [['id', 'title']],
                'comments' => [['id', 'content']]
            ]
        ]);
    }
}
