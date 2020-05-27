<?php

namespace App;

use App\Article;
use App\Author;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Restql\Traits\RestqlAttributes;

class Comment extends Model
{
    use RestqlAttributes;

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
}
