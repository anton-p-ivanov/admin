<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Lesson $model
 * @var \app\models\Workflow $workflow
 * @var string $title
 */

use app\widgets\form\ActiveForm;
use training\models\Course;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'lessons-form',
            'data-type' => 'active-form',
        ]
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('training/lessons', $title); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'active')->switch(); ?>
        <?= $form->field($model, 'course_uuid')->dropDownList(Course::getList()); ?>
        <?= $form->field($model, 'title'); ?>
        <?= $form->field($model, 'description')->multilineInput(); ?>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'code'); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'sort'); ?>
            </div>
        </div>
        <?= $form->field($workflow, 'status')->dropDownList(
            \app\models\WorkflowStatus::getList(),
            ['dropdown' => ['class' => 'dropdown dropdown_wide dropdown_up']]
        ); ?>
    </div>
    <div class="modal__footer grid">
        <div class="grid__item">
            <div class="form-group__required form-group__hint">
                * <?= Yii::t('training/lessons', 'Required fields'); ?>
            </div>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>