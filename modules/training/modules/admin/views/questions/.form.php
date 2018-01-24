<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Question $model
 * @var string $course_uuid
 * @var string $title
 */

use app\widgets\form\ActiveForm;
use training\models\Lesson;
use training\models\Question;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'questions-form',
            'data-type' => 'active-form',
        ]
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('training/questions', $title); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'active')->switch(); ?>
        <?= $form->field($model, 'lesson_uuid')->dropDownList(Lesson::getList($course_uuid)); ?>
        <?= $form->field($model, 'title'); ?>
        <?= $form->field($model, 'description')->multilineInput(); ?>
        <?= $form->field($model, 'type')->dropDownList(Question::getTypes()); ?>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'value'); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'sort'); ?>
            </div>
        </div>
    </div>
    <div class="modal__footer grid">
        <div class="grid__item">
            <div class="form-group__required form-group__hint">
                * <?= Yii::t('training/questions', 'Required fields'); ?>
            </div>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>