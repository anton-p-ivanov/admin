<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \catalogs\modules\admin\modules\fields\models\Field $field
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('forms', \forms\Module::$title),
    Yii::t('forms', 'Fields')
);
?>

<?= $this->render('@fields/views/values/index.php', [
    'dataProvider' => $dataProvider,
    'returnUrl' => \yii\helpers\Url::to(['fields/index', 'form_uuid' => $field->form_uuid]),
    'field' => $field
]); ?>