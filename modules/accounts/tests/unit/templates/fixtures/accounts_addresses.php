<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'account_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'account-' . $index)->toString(),
    'address_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'address-' . $index)->toString(),
];