<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \catalogs\models\Element $element
 * @var array $properties
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('catalogs/elements', 'Catalogs'),
    $element->catalog->title,
    Yii::t('catalogs/elements', 'Elements')
);

?>

<?= $this->render('@fields/views/properties/index', [
    'dataProvider' => $dataProvider,
    'properties' => $properties,
    'returnUrl' => ['elements/index', 'tree_uuid' => Yii::$app->request->get('tree_uuid')],
    'editUrl' => ['edit', 'element_uuid' => $element->uuid, 'field_uuid' => null],
    'title' => $element->title
]); ?>