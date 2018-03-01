<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \fields\models\Property[] $properties
 * @var \users\models\User $user
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('users', \users\Module::$title),
    Yii::t('users/properties', 'Custom fields')
);
?>

<?= $this->render('@fields/views/properties/index', [
    'dataProvider' => $dataProvider,
    'properties' => $properties,
    'returnUrl' => ['users/index'],
    'editUrl' => ['edit', 'user_uuid' => $user->uuid, 'field_uuid' => null],
    'title' => $user->getFullName()
]); ?>