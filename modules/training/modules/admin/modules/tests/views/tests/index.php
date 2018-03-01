<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \training\modules\admin\models\Course $course
 * @var array $questions
 * @var array $attempts
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('training', \training\modules\admin\Module::$title),
    Yii::t('training/tests', 'Tests')
);

// Registering assets
\training\modules\admin\modules\tests\assets\TestsAsset::register($this);

?>
<div class="course-title">
    <?= Yii::t('training/tests', 'Tests for training course'); ?> "<?= $course->title; ?>"
</div>
<div id="tests-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'tests-grid',
        'dataProvider' => $dataProvider,
        'columns' => require_once ".grid.php",
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
    ]); ?>

</div>

<div class="modal modal_warning" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>