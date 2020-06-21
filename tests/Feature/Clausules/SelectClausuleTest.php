<?php

namespace Testing\Feature\Clausules;

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

    /**
    * @test Get specific attributes from diferents models.
    */
    public function getSpecificAttributesFromDiferentsModels(): void
    {
        $response = $this->json('get', 'restql', [
            'authors' => [
                'select' => ['name', 'email']
            ],
            'articles' => [
                'select' => 'title',
                'take' => 20
            ],
            'comments' => [
                'select' => ['content']
            ]
        ]);

        $response->assertJsonCount(3, 'data');
        $response->assertJsonCount(15, 'data.authors');
        $response->assertJsonCount(20, 'data.articles');
        $response->assertJsonCount(15, 'data.comments');

        $response->assertJsonStructure([
            'data' => [
                'authors' => [['id', 'name', 'email']],
                'articles' => [['id', 'title']],
                'comments' => [['id', 'content']]
            ]
        ]);
    }

    /**
     * @test Can't get specifc attributes from determinated model.
     */
    public function cantGetProtectedAttributesFromArticleModel(): void
    {
        $publicAttr = (new \Testing\App\Article())->onSelectFillables();

        $response = $this->json('get', 'restql', [
            'articles' => [
                /// TODO: Document this.
                'select' => array_merge($publicAttr, ['public'])
            ]
        ]);

        $response->assertJsonStructure([
            'data' => [
                'articles' => [array_merge($publicAttr, ['id'])]
            ]
        ]);

        $response->assertDontSee("\"public\":", false);
    }
}
