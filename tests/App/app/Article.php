<?php

namespace Testing\App;

use Testing\App\Author;
use Testing\App\Comment;
use Restql\Traits\RestqlAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factory as LegacyFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class Article extends Model
{
    use RestqlAttributes;

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted(): void
    {
        self::addGlobalScope(function ($builder) {
            $builder->wherePublic(true);
        });
    }

    /**
     * Get the article author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Get the article comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the fillable attributes for selects.
     *
     * @return array
     */
    public function onSelectFillables(): array
    {
        // You cand replace this with custom authorization.
        return ['title', 'content'];
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
            public $model = Article::class;
            public function definition()
            {
                return Article::factoryDefinition($this->faker);
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
            'public' => true,
            'title' => $faker->text,
            'content' => $faker->text(rand(500, 1000)),
            'author_id' => Author::factory()
        ];
    }
}
