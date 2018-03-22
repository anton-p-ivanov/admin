<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \training\models\Attempt $attempt
 * @var array $properties
 */

$this->title = sprintf('%s — %s',
    Yii::t('training', \training\Module::$title),
    Yii::t('training/attempts', 'Attempts')
);

$lesson_uuid = null;

// Registering assets
\fields\assets\PropertiesAsset::register($this);
?>

<div id="properties-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'properties-grid',
        'dataProvider' => $dataProvider,
        'columns' => require_once ".grid.php",
        'showFooter' => true,
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
        'beforeRow' => function (\training\models\Question $model, $key, $index, $grid) use (&$lesson_uuid) {
            if (!$lesson_uuid || $model->lesson_uuid !== $lesson_uuid) {
                $lesson_uuid = $model->lesson_uuid;
                return '<tr><td colspan="' . count($grid->columns) . '">' . $model->lesson->title . '</td></tr>';
            }

            return null;
        }
    ]); ?>

</div>

<div class="modal modal_warning" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>