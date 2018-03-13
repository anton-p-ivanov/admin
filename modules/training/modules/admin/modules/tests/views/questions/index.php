<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Test $test
 * @var \training\modules\admin\models\Lesson[] $lessons
 * @var array $selected
 */

use app\widgets\form\ActiveForm;

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('training/tests', \training\modules\admin\modules\tests\Module::$title),
    Yii::t('training/tests', 'Questions')
);

// Registering assets
\training\modules\admin\modules\tests\assets\QuestionsAsset::register($this);

?>
<div class="section-title">
    <?= Yii::t('training/tests', 'Questions for test'); ?> "<?= $test->title; ?>"
</div>

<?php $form = ActiveForm::begin([
    'action' => ['select', 'test_uuid' => $test->uuid],
    'options' => [
        'id' => 'questions-form',
        'data-type' => 'active-form',
    ]
]); ?>

<div id="questions-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?php if ($lessons): ?>
        <?php foreach ($lessons as $lesson): ?>
            <?php if ((int) $lesson->getQuestions()->count() === 0): ?>
                <?php continue; ?>
            <?php endif; ?>
            <?= $this->render('index_questions', [
                'test' => $test,
                'lesson' => $lesson,
                'selected' => $selected
            ]); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="grid-view__empty">
            <div class="grid-view__empty-content">
                <?= $this->render('index_empty', ['test' => $test]); ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php ActiveForm::end(); ?>