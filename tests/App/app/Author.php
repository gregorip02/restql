<?php

namespace Testing\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Testing\App\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Restql\Traits\RestqlAttributes;
use Testing\Database\Factories\AuthorFactory;

class Author extends Model
{
    use RestqlAttributes;
    use HasFactory;

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

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory(int $count = 1)
    {
        return new AuthorFactory($count);
    }
}
