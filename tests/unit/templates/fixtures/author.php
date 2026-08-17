<?php

declare(strict_types=1);

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'full_name' => $faker->parse('{{lastName}} {{firstName}} {{middleName}}'),
];
