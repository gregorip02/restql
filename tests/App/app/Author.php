<?php

namespace Testing\App;

use Testing\App\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Restql\Traits\RestqlAttributes;
use Testing\Database\Factories\AuthorFactory;

class Author extends Model
{
    use RestqlAttributes;
    use CreateFactory;

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
     * @param  int|null $count
     * @param  array $state
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public static function factory(?int $count = null, array $state = [])
    {
        $factory = new AuthorFactory();

        return static::createFactory($factory, $count, $state);
    }
}
