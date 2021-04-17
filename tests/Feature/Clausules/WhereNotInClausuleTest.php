<?php

namespace Testing\Feature\Clausules;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Testing\App\Article;
use Testing\TestCase;

final class WhereNotInClausuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Fake not in articles.
     *
     * @var array
     */
    protected $articles = [4, 5, 9, 10];

    /**
     * @test Exclude articles where id not in (4, 5, 9, 10)
     */
    public function excludeArticlesWhereIdNotInUsingImplicitMethod(): void
    {
        Article::factory(19)->create();

        $response = $this->json('get', 'restql', [
            'articles' => [
                'select'     => 'id',
                'whereNotIn' => [$this->articles]
            ]
        ]);

        /// We get 15 by default because we are excluding [$this->articles]
        /// and this is rejected with others articles.
        $response->assertJsonCount(15, 'data.articles');
    }
}
