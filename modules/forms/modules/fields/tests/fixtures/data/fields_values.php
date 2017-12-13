<?php

$fields = \forms\modules\fields\models\Field::find()->all();
$faker = \Faker\Factory::create();

$return = [];
foreach ($fields as $field) {
    for ($i = 0; $i < 10; $i++) {
        $random = Yii::$app->security->generateRandomString(6);
        $return[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'field_uuid' => $field->uuid,
            'value' => $faker->text(),
            'label' => $faker->text(),
        ];
    }
}

return $return;