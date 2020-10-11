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

        $ids = $response->json('data.authors.*.id');
        foreach ($ids as $id) {
            $this->assertSame(12, $id);
        }
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

        $ids = $response->json('data.articles.*.id');
        foreach ($ids as $id) {
            $this->assertSame(10, $id);
        }
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

        $response->assertJsonCount(1, 'data.comments');

        $id = $response->json('data.comments.0.id');

        $this->assertEquals(1, $id);
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

        $response->assertJsonCount(1, 'data.articles');

        $id = $response->json('data.articles.0.id');

        $this->assertEquals(10, $id);
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

        $response->assertJsonCount(1, 'data.articles');

        $id = $response->json('data.articles.0.id');

        $this->assertEquals(20, $id);
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
