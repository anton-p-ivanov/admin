<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $validators
 * @var array $values
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('accounts', \accounts\Module::$title),
    Yii::t('fields', 'Fields')
);

// Registering assets
\accounts\modules\admin\modules\fields\assets\FieldsAsset::register($this);

?>

<?= $this->render('@fields/views/fields/index.php', [
    'dataProvider' => $dataProvider,
    'validators' => $validators,
    'values' => $values,
]); ?>