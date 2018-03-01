<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \training\modules\admin\models\Lesson $lesson
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('training', \training\modules\admin\Module::$title),
    Yii::t('training/questions', 'Questions')
);

// Registering assets
\training\modules\admin\assets\QuestionsAsset::register($this);

?>
<div class="lesson-title">
    <?= Yii::t('training/questions', 'Questions for lesson'); ?> "<?= $lesson->title; ?>"
</div>
<div id="questions-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'questions-grid',
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