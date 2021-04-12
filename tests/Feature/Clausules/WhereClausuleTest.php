<?php

namespace Testing\Feature\Clausules;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Restql\Exceptions\MissingArgumentException;
use Testing\App\Comment;
use Testing\App\Author;
use Testing\App\Article;
use Testing\TestCase;

final class WhereClausuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test Test where clausule using explicit method.
     */
    public function getAuthorByIdUsingExplicitMethod(): void
    {
        Author::factory()->create(['id' => $id = rand(100, 200)]);

        $response = $this->json('get', 'restql', [
            'authors' => [
                'where' => [
                    'column' => 'id',
                    'operator' => '=',
                    'value' => $id
                ]
            ]
        ]);

        $response->assertJsonStructure([
            'data' => ['authors' => [['id']]]
        ]);

        $response->assertJsonCount(1, 'data.authors');
        $this->assertSame($response->json('data.authors.0.id'), $id);
    }

    /**
     * @test Get article by id using explicit method without operator key.
     */
    public function getArticleByIdUsingExplicitMethodWithoutOperatorKey(): void
    {
        Article::factory()->create(['id' => $id = rand(100, 200)]);

        $response = $this->json('get', 'restql', [
            'articles' => [
                'where' => [
                    'column' => 'id',
                    'value' => $id
                ]
            ]
        ]);

        $response->assertJsonStructure([
            'data' => ['articles' => [['id']]]
        ]);

        $response->assertJsonCount(1, 'data.articles');
        $this->assertSame($response->json('data.articles.0.id'), $id);
    }

    /**
     * @test Get comment by id using explicit method without value and operator key.
     */
    public function getCommentByIdUsingExplicitMethodWithoutValueAndOperatorKey(): void
    {
        Comment::factory()->create(['id' => $id = rand(100, 200)]);

        $response = $this->json('get', 'restql', [
            'comments' => [
                'where' => [
                    // The clausule will inject the model primary key name
                    // as column, this key is "id" see CreateCommentsTable on migrations.
                    'value' => $id
                ]
            ]
        ]);

        $response->assertJsonStructure([
            'data' => ['comments' => [['id']]]
        ]);

        $response->assertJsonCount(1, 'data.comments');
        $this->assertSame($response->json('data.comments.0.id'), $id);
    }

    /**
     * @test Failed if the client dont send a value on explicit method.
     */
    public function failedWithMissingArgumentException(): void
    {
        $response = $this->json('get', 'restql', [
            'articles' => [
                'where' => ['column' => 'id']
            ]
        ]);

        $response->assertStatus(500);

        $this->assertEquals(MissingArgumentException::class, get_class($response->exception));
    }

    /**
     * @test Get article by id using super implicit method.
     */
    public function getArticleByIdUsingSuperImplicitMehtod(): void
    {
        Article::factory()->create(['id' => $id = random_int(100, 200)]);

        $response = $this->json('get', 'restql', [
            'articles' => [
                'where' => $id
            ]
        ]);

        $response->assertJsonStructure([
            'data' => ['articles' => [['id']]]
        ]);

        $response->assertJsonCount(1, 'data.articles');
        $this->assertSame($response->json('data.articles.0.id'), $id);
    }

    /**
     * @test Get article by id using implicit method.
     */
    public function getArticleByIdUsingImplicitMehtod(): void
    {
        Article::factory()->create(['id' => 20]);

        $response = $this->json('get', 'restql', [
            'articles' => [
                'where' => ['id', $id = 20]
            ]
        ]);

        $response->assertJsonStructure([
            'data' => ['articles' => [['id']]]
        ]);

        $response->assertJsonCount(1, 'data.articles');
        $this->assertSame($response->json('data.articles.0.id'), $id);
    }

    /**
     * @test Get author different to id.
     */
    public function getAuthorByIdDifferentTo(): void
    {
        Author::factory(10)->create();

        $response = $this->json('get', 'restql', [
            'authors' => [
                'where' => ['id', '!=', 10]
            ]
        ]);
        $response->assertJsonCount(9, 'data.authors');
        $response->assertDontSee("\"id\":10", false);
        $response->assertJsonMissing([
            'id' => 10
        ]);
    }

    /**
     * @test Get author different to id.
     */
    public function getArticleByIdMoreThan(): void
    {
        Author::factory($count = rand(10, 20))->create();

        $response = $this->json('get', 'restql', [
            'authors' => [
                'where' => ['id', '>', 10]
            ]
        ]);

        $response->assertJsonCount($count - 10, 'data.authors');
        $response->assertDontSee("\"id\":10", false);
        $response->assertJsonMissing([
            'id' => 10
        ]);
        $response->assertDontSee("\"id\":21", false);
        $response->assertJsonMissing([
            'id' => 21
        ]);
    }
}
