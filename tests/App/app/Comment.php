<?php

namespace Testing\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Testing\App\Article;
use Testing\App\Author;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Restql\Traits\RestqlAttributes;
use Testing\Database\Factories\CommentFactory;

class Comment extends Model
{
    use RestqlAttributes;
    use HasFactory;

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
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory(int $count = 1)
    {
        return new CommentFactory($count);
    }
}
