<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'mail-type-' . $index)->toString(),
    'code' => 'MAIL_TYPE_' . $index,
    'title' => $faker->text(),
    'description' => $faker->text(),
];