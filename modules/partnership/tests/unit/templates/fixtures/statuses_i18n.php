<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

$languages = ['en-US', 'ru-RU'];
$titles = [
    'en-US' => $faker->text(),
    'ru-RU' => $faker->text(),
];

return [
    'status_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'status-0')->toString(),
    'lang' => $languages[$index],
    'title' => $titles[$languages[$index]],
];