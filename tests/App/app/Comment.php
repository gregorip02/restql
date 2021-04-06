<?php

namespace Testing\App;

use Testing\App\Article;
use Testing\App\Author;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Restql\Traits\RestqlAttributes;
use Testing\Database\Factories\CommentFactory;

class Comment extends Model
{
    use RestqlAttributes;
    use CreateFactory;

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
        $factory = new CommentFactory();

        return static::createFactory($factory, $count, $state);
    }
}
