<?php

namespace Testing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Testing\App\Article;
use Testing\App\Author;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text,
            'content' => $this->faker->text(rand(500, 1000)),
            'public' => true,
            'author_id' => Author::factory()
        ];
    }

    /**
     * Set article as private.
     *
     * @return self
     */
    public function isPrivate()
    {
        return $this->state(['public' => false]);
    }

    /**
     * Set article as public.
     *
     * @return self
     */
    public function isPublic()
    {
        return $this->state(['public' => true]);
    }
}
