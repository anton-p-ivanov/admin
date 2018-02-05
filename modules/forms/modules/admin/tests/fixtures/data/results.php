<?php


use forms\modules\admin\modules\fields\models\Field;
use Ramsey\Uuid\Uuid;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

Yii::setAlias('@forms', '@app/modules/forms');

$form_uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-0')->toString();
$faker = \Faker\Factory::create();

if (!function_exists('generateResultData')) {
    /**
     * @param \Faker\Generator $faker
     * @param string $form_uuid
     * @return string
     */
    function generateResultData($faker, $form_uuid)
    {
        $fields = ArrayHelper::getColumn(Field::findAll(['form_uuid' => $form_uuid]), 'code');
        $data = [];

        foreach ($fields as $field) {
            $data[$field] = $faker->text(500);
        }

        return Json::encode($data);
    }
}

$results = [];

for ($index = 0; $index < 2; $index++) {
    $results[] = [
        'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-result-' . $index)->toString(),
        'data' => generateResultData($faker, $form_uuid),
        'form_uuid' => $form_uuid,
        'status_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'status-0')->toString()
    ];
}

return $results;