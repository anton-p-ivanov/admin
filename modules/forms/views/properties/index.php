<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \forms\models\Result $result
 * @var array $properties
 */

$this->title = sprintf('%s — %s',
    Yii::t('forms', \forms\Module::$title),
    Yii::t('forms/results', 'Results')
);

?>

<?= $this->render('@fields/views/properties/index', [
    'dataProvider' => $dataProvider,
    'properties' => $properties,
    'returnUrl' => ['results/index', 'form_uuid' => $result->form_uuid],
    'editUrl' => ['edit', 'result_uuid' => $result->uuid, 'field_uuid' => null],
    'title' => Yii::t('forms/results', 'Form result properties')
]); ?>