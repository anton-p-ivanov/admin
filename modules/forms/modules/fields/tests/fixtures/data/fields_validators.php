<?php

$fields = \forms\modules\fields\models\Field::find()->all();

$return = [];
$index = 0;

foreach ($fields as $field) {
    foreach (\forms\modules\fields\models\FieldValidator::getTypes() as $type => $name) {
        $random = Yii::$app->security->generateRandomString(6);
        $return[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'field_uuid' => $field->uuid,
            'type' => $type,
        ];
        $index++;
    }
}

return $return;