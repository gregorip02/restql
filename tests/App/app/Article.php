<?php

namespace Testing\App;

use Testing\App\Author;
use Testing\App\Comment;
use Restql\Traits\RestqlAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use RestqlAttributes;

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
}
