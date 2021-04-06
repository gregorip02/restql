<?php

namespace Testing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Testing\App\Article;
use Testing\App\Author;
use Testing\App\Comment;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->text(rand(20, 250)),
            'public' => rand(0, 1),
            'article_id' => Article::factory(),
            'author_id' => Author::factory()
        ];
    }
}
