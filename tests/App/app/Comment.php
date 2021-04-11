<?php

namespace Testing\App;

use Closure;
use Testing\App\Article;
use Testing\App\Author;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Restql\Traits\RestqlAttributes;
use Illuminate\Database\Eloquent\Factory;

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
     * @param  array $state
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public static function factory(?int $count = null, array $state = [])
    {
        if (function_exists('factory')) {
            app(Factory::class)->define(static::class, static::factoryDefinition());
            return factory(static::class, $count);
        }
    }

    /**
     * Default definition of the model factory.
     *
     * @return Closure
     */
    public static function factoryDefinition(): Closure
    {
        return function ($faker): array {
            return [
                'content' => $faker->text(rand(20, 250)),
                'public' => rand(0, 1),
                'article_id' => Article::factory(),
                'author_id' => Author::factory()
            ];
        };
    }
}
