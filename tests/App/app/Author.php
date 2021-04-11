<?php

namespace Testing\App;

use Closure;
use Testing\App\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Restql\Traits\RestqlAttributes;
use Illuminate\Database\Eloquent\Factory;

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
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address
            ];
        };
    }
}
