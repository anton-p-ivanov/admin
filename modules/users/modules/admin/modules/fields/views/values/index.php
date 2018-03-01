<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \catalogs\modules\admin\modules\fields\models\Field $field
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('users', \users\Module::$title),
    Yii::t('fields/values', 'Values')
);
?>

<?= $this->render('@fields/views/values/index.php', [
    'dataProvider' => $dataProvider,
    'field' => $field,
]); ?>