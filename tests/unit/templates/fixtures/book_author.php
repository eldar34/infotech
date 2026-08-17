<?php

declare(strict_types=1);

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
// $index увеличивается с каждым шагом. Привяжем каждую сгенерированную книгу
// к случайному автору (предполагаем, что сгенерируем по 30 записей)
return [
    'book_id' => $index + 1,
    'author_id' => rand(1, 30),
];
