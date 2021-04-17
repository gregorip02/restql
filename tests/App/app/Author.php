<?php

namespace Testing\App;

use Closure;
use Testing\App\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Restql\Traits\RestqlAttributes;
use Illuminate\Database\Eloquent\Factory as LegacyFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     * @return Factory|LegacyFactory
     */
    public static function factory(?int $count = null)
    {
        if (function_exists('factory')) {
            app(LegacyFactory::class)->define(static::class, function ($faker) {
                return static::factoryDefinition($faker);
            });

            return factory(static::class, $count);
        }

        return new class($count) extends Factory {
            public $model = Author::class;
            public function definition()
            {
                return Author::factoryDefinition($this->faker);
            }
        };
    }

    /**
     * Default definition of the model factory.
     *
     * @param \Faker\Generator $faker
     * @return array
     */
    public static function factoryDefinition($faker): array
    {
        return [
            'name' => $faker->name,
            'email' => $faker->unique()->email,
            'phone' => $faker->phoneNumber,
            'address' => $faker->address
        ];
    }
}
