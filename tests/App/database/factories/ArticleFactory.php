<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define('App\Article', function (Faker $faker) {
    return [
        'title' => $faker->unique()->text(25),
        'content' => $faker->text(rand(500, 1000)),
        'public' => rand(0, 1),
        'author_id' => rand(1, 100)
    ];
});
