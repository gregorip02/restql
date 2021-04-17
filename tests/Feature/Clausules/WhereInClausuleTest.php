<?php

namespace Testing\Feature\Clausules;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Testing\App\Article;
use Testing\TestCase;

final class WhereInClausuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Fake in articles id.
     *
     * @var array
     */
    protected $articles = [1, 3, 10, 14];

    /**
     * @test Get articles where id in (1, 14, 3, 10)
     */
    public function getArticlesWhereIdInUsingImplicitMethod(): void
    {
        Article::factory(14)->create();

        $response = $this->json('get', 'restql', [
            'articles' => [
                'whereIn' => [$this->articles],
                'select'  => 'id'
            ]
        ]);

        $response->assertJsonCount(count($this->articles), 'data.articles');
        $response->assertExactJson([
            'data' => [
                'articles' => array_map(function (int $id) {
                    return ['id' => $id];
                }, $this->articles)
            ]
        ]);
    }
}
