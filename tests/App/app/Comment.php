<?php

namespace Testing\App;

use Testing\App\Article;
use Testing\App\Author;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Restql\Traits\RestqlAttributes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factory as LegacyFactory;

class Comment extends Model
{
    use RestqlAttributes;

    /**
     * Fillable attributes for the model.
     *
     * @var array
     */
    protected $fillable = ['content'];

    /**
     * Get the comment author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Get the comment article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @param  int|null $count
     * @return Factory|LegacyFactory
     */
    public static function factory(?int $count = null)
    {
        if (function_exists('factory')) {
            app(LegacyFactory::class)->define(static::class, function ($faker) {
                return static::factoryDefinition($faker);
            });

            return factory(static::class, $count);
        }

        return new class($count) extends Factory {
            public $model = Comment::class;
            public function definition()
            {
                return Comment::factoryDefinition($this->faker);
            }
        };
    }

    /**
     * Default definition of the model factory.
     *
     * @param \Faker\Generator $faker
     * @return array
     */
    public static function factoryDefinition($faker): array
    {
        return [
            'content' => $faker->text(rand(20, 250)),
            'public' => rand(0, 1),
            'article_id' => Article::factory(),
            'author_id' => Author::factory()
        ];
    }
}
