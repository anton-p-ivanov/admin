<?php


use forms\modules\admin\modules\fields\models\Field;
use Ramsey\Uuid\Uuid;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

Yii::setAlias('@forms', '@app/modules/forms');

$form_uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-0')->toString();
$faker = \Faker\Factory::create();

$results = [];

for ($index = 0; $index < 2; $index++) {
    $results[] = [
        'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-result-' . $index)->toString(),
        'form_uuid' => $form_uuid,
        'status_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'status-0')->toString(),
        'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-result-0')->toString()
    ];
}

return $results;