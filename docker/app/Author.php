<?php

namespace App;

use App\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    /**
    * The accessors to append to the model's array form.
    *
    * @var array
    */
    protected $appends = ['count_articles'];

    /**
     * Get the author articles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Count the articles published by an author.
     *
     * @return int
     */
    public function getCountArticlesAttribute(): int
    {
        return $this->articles()->count();
    }
}
