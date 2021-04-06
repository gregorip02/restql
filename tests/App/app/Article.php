<?php

namespace Testing\App;

use Testing\App\Author;
use Testing\App\Comment;
use Restql\Traits\RestqlAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Testing\Database\Factories\ArticleFactory;

class Article extends Model
{
    use RestqlAttributes;
    use CreateFactory;

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
        $factory = new ArticleFactory();

        return static::createFactory($factory, $count, $state);
    }
}
