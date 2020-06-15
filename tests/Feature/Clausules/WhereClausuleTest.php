<?php

namespace Testing\Feature\Clausules;

use Restql\Exceptions\MissingArgumentException;
use Testing\TestCase;

final class WhereClausuleTest extends TestCase
{
    /**
     * @test Test where clausule using explicit method.
     */
    public function getAuthorByIdUsingExplicitMethod(): void
    {
        $response = $this->json('get', 'restql', [
            'authors' => [
                'where' => [
                    'column' => 'id',
                    'operator' => '=',
                    'value' => 12
                ]
            ]
        ]);

        $response->assertJsonStructure([
            'data' => ['authors' => [['id']]]
        ]);

        $response->assertJsonCount(1, 'data.authors');

        $author = $response->decodeResponseJson('data.authors.0');

        $this->assertEquals(12, $author['id']);
    }

    /**
     * @test Get article by id using explicit method without operator key.
     */
    public function getArticleByIdUsingExplicitMethodWithoutOperatorKey(): void
    {
        $response = $this->json('get', 'restql', [
            'articles' => [
                'where' => [
                    'column' => 'id',
                    'value' => 10
                ]
            ]
        ]);

        $response->assertJsonStructure([
            'data' => ['articles' => [['id']]]
        ]);

        $response->assertJsonCount(1, 'data.articles');

        $article = $response->decodeResponseJson('data.articles.0');

        $this->assertEquals(10, $article['id']);
    }

    /**
     * @test Get comment by id using explicit method without value and operator key.
     */
    public function getCommentByIdUsingExplicitMethodWithoutValueAndOperatorKey(): void
    {
        $response = $this->json('get', 'restql', [
            'comments' => [
                'where' => [
                    // The clausule will inject the model primary key name
                    // as column, this key is "id" see CreateCommentsTable on migrations.
                    'value' => 1
                ]
            ]
        ]);

        $response->assertJsonStructure([
            'data' => ['comments' => [['id']]]
        ]);

        $response->assertJsonCount(1, 'data.comments');

        $comment = $response->decodeResponseJson('data.comments.0');

        $this->assertEquals(1, $comment['id']);
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
        $response = $this->json('get', 'restql', [
            'articles' => [
                'where' => 10
            ]
        ]);

        $response->assertJsonStructure([
            'data' => ['articles' => [['id']]]
        ]);

        $response->assertJsonCount(1, 'data.articles');

        $article = $response->decodeResponseJson('data.articles.0');

        $this->assertEquals(10, $article['id']);
    }

    /**
     * @test Get article by id using implicit method.
     */
    public function getArticleByIdUsingImplicitMehtod(): void
    {
        $response = $this->json('get', 'restql', [
            'articles' => [
                'where' => ['id', 20]
            ]
        ]);

        $response->assertJsonStructure([
            'data' => ['articles' => [['id']]]
        ]);

        $response->assertJsonCount(1, 'data.articles');

        $article = $response->decodeResponseJson('data.articles.0');

        $this->assertEquals(20, $article['id']);
    }

    /**
     * @test Get author different to id.
     */
    public function getAuthorByIdDifferentTo(): void
    {
        $response = $this->json('get', 'restql', [
            'authors' => [
                'where' => ['id', '!=', 10]
            ]
        ]);

        $response->assertJsonCount(15, 'data.authors');

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
        $response = $this->json('get', 'restql', [
            'authors' => [
                'where' => ['id', '>', 10]
            ]
        ]);

        $response->assertJsonCount(15, 'data.authors');

        $response->assertDontSee("\"id\":10", false);

        $response->assertJsonMissing([
            'id' => 10
        ]);

        $response->assertDontSee("\"id\":26", false);

        $response->assertJsonMissing([
            'id' => 26
        ]);
    }
}
