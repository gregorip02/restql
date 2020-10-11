<?php

namespace Testing\Feature\Clausules;

use Testing\TestCase;

final class WhereNotInClausuleTest extends TestCase
{
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
        $response = $this->json('get', 'restql', [
            'articles' => [
                'select'     => 'id',
                'whereNotIn' => [$this->articles]
            ]
        ]);

        /// We get 15 by default because we are excluding [$this->articles]
        /// and this is rejected with others articles.
        $response->assertJsonCount(15, 'data.articles');

        $articles = $response->json('data.articles.*.id');

        /// Don't see any article id defined in $this->articles in
        /// $articles responsed by RestQL.
        foreach ($this->articles as $article) {
            $this->assertFalse(
                in_array($article, $articles)
            );
        }
    }
}
