<?php

use forms\modules\fields\models\Field;
use Ramsey\Uuid\Uuid;

$faker = \Faker\Factory::create();
$form_uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-0')->toString();
$fields = \yii\helpers\ArrayHelper::getColumn(Field::findAll(['form_uuid' => $form_uuid]), 'code');
$data = [];

foreach ($fields as $field) {
    $data[$field] = $faker->text();
}

return [
    [
        'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-result-0')->toString(),
        'data' => \yii\helpers\Json::encode($data),
        'form_uuid' => $form_uuid,
    ]
];