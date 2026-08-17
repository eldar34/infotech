<?php

declare(strict_types=1);

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'author_id' => rand(1, 30),
    'email' => $faker->unique()->email,
    'created_at' => time(),
    'updated_at' => time(),
];
