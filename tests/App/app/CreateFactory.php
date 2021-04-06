<?php

namespace Testing\App;

use Exception;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

trait CreateFactory
{
    /**
     * Create factory with Laravel +5.8 compatibility.
     *
     * @param  Factory  $factory
     * @param  int|null $count
     * @param  array    $state
     * @return Factory
     */
    protected static function createFactory(Factory $factory, ?int $count = null, array $state = [])
    {
        if (trait_exists(HasFactory::class)) {
            return $factory->count($count)->state($state);
        }

        if (function_exists('factory')) {
            return factory(static::class)->times($count)->state($state);
        }

        throw new Exception('Factory exception', 1);
    }
}
