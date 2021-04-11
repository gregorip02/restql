<?php

namespace Testing\App;

use Closure;
use Testing\App\Author;
use Testing\App\Comment;
use Restql\Traits\RestqlAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factory;

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
                'title' => $faker->text,
                'content' => $faker->text(rand(500, 1000)),
                'public' => true,
                'author_id' => Author::factory()
            ];
        };
    }
}
