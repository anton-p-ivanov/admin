<?php
/**
 * @var \yii\web\View $this
 * @var \training\models\AttemptData $model
 * @var \training\models\Question $question
 */

use app\widgets\form\ActiveForm;

?>

<div class="modal__container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'property-form',
            'data-type' => 'active-form',
        ]
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('training/attempts', 'Update answer'); ?></div>
    </div>
    <div class="modal__body">

        <?php $field = $form->field($model, 'answer_uuid')
                ->label($question->title)
                ->hint($question->description)
                ->cleanButton();  ?>

        <?php $answers = $question->getAnswers()->indexBy('uuid')->select('answer')->column(); ?>

        <?php switch ($question->type) {
            case (\training\models\Question::TYPE_SINGLE):
                echo $field->radioList($answers);
                break;
            case (\training\models\Question::TYPE_MULTIPLE):
                echo $field->checkboxList($answers);
                break;
            default:
                echo $field->textarea();
                break;
        } ?>

    </div>
    <div class="modal__footer">
        <div class="grid__item"></div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>