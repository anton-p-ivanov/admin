<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'address-' . $index)->toString(),
    'type_uuid' => \app\models\AddressType::find()->orderBy(new \yii\db\Expression('RAND()'))->one()->{'uuid'},
    'country_code' => \app\models\AddressCountry::find()->orderBy(new \yii\db\Expression('RAND()'))->one()->{'code'},
    'region' => '',
    'city' => $faker->city,
    'zip' => $faker->postcode,
    'address' => $faker->address,
];