<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \catalogs\modules\admin\modules\fields\models\Field $field
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('catalogs', \catalogs\Module::$title),
    Yii::t('fields/validators', 'Validators')
);

?>
<?= $this->render('@fields/views/validators/index.php', [
    'dataProvider' => $dataProvider,
    'field' => $field,
    'returnUrl' => \yii\helpers\Url::to(['fields/index', 'catalog_uuid' => $field->catalog_uuid])
]); ?>