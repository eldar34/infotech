<?php

declare(strict_types=1);

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'title' => rtrim($faker->realText(rand(20, 50)), '. '),
    'release_year' => rand(2000, 2026),
    'description' => $faker->realText(rand(150, 300)),
    'isbn' => $faker->isbn13(),
    'cover_image' => null,
];
