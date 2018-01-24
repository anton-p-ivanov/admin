<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \training\modules\admin\models\Question $question
 * @var array $questions
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('training', \training\modules\admin\Module::$title),
    Yii::t('training/answers', 'Answer')
);

// Registering assets
\training\modules\admin\assets\AnswersAsset::register($this);

?>
<div class="question-title">
    <?= Yii::t('training/answers', 'Answers for question'); ?> "<?= $question->title; ?>"
</div>
<div id="answers-pjax" data-pjax-container="true">

    <?php if (!$question->hasValidAnswer()): ?>
        <div class="alert alert_warning">
            There is no any valid answer for selected question.
            Please, choose a valid one.
        </div>
    <?php endif; ?>

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'answers-grid',
        'dataProvider' => $dataProvider,
        'columns' => require_once ".grid.php",
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
    ]); ?>

</div>

<div class="modal" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>