<?php

$field = \users\modules\fields\models\Field::findOne(['code' => 'USER_FIELD_TEST_01']);

$return = [];

for ($i = 0; $i < 10; $i++) {
    $random = Yii::$app->security->generateRandomString(6);
    $return['value' . $i] = [
        'uuid' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'field_uuid' => $field->uuid,
        'value' => 'test value ' . $random,
        'label' => 'test label ' . $random,
    ];
}

return $return;