<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'form_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-' . $index)->toString(),
    'type_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'mail-type-' . $index)->toString(),
];