<?php

namespace Testing\Database\Seeds;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Testing\App\Article;
use Testing\App\Author;
use Testing\App\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $authors = Author::factory(20)->create();

            $articles = Article::factory(50)->create();

            Comment::factory(70)->state(new Sequence(
                ...array_fill(0, 70, [
                    'author_id' => $authors->random(),
                    'article_id' => $articles->random(),
                ])
            ))->create();
        });
    }
}
