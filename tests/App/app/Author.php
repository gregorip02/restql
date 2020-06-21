<?php

namespace Testing\App;

use Testing\App\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Restql\Traits\RestqlAttributes;

class Author extends Model
{
    use RestqlAttributes;

    /**
     * Fillable attributes for the model.
     *
     * @var array
     */
    protected $fillable = ['name', 'email'];

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
     * Get the fillable attributes for creations.
     *
     * @return array
     */
    public function onCreateFillables(): array
    {
        return ['name', 'email', 'phone', 'address'];
    }
}
