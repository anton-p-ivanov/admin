<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'uuid' => \Ramsey\Uuid\Uuid::uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_URL, 'site-' . $index)->toString(),
    'active' => true,
    'title' => $faker->text(),
    'url' => $faker->url,
    'email' => $faker->email,
    'sort' => 100,
    'code' => 'SITE_' . $index,
];