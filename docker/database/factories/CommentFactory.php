<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define('App\Comment', function (Faker $faker) {
    return [
        'content' => $faker->text(rand(20, 250)),
        'public' => rand(0, 1),
        'article_id' => rand(1, 300),
        'author_id' => rand(1, 100),
    ];
});
