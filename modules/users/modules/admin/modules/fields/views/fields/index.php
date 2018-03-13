<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $validators
 * @var array $values
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('users', \users\Module::$title),
    Yii::t('fields', 'Fields')
);
?>

<?= $this->render('@fields/views/fields/index.php', [
    'dataProvider' => $dataProvider,
    'validators' => $validators,
    'values' => $values,
    'useGroups' => false
]); ?>