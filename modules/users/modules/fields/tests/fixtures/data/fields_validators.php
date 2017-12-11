<?php

$field = \users\modules\fields\models\Field::findOne(['code' => 'USER_FIELD_TEST_01']);

$return = [];
$index = 0;

foreach (\users\modules\fields\models\FieldValidator::getTypes() as $type => $name) {
    $random = Yii::$app->security->generateRandomString(6);
    $return['validator' . $index] = [
        'uuid' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'field_uuid' => $field->uuid,
        'type' => $type,
    ];
    $index++;
}

return $return;