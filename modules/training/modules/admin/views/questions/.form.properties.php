<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Question $model
 * @var \app\widgets\form\ActiveForm $form
 * @var string $course_uuid
 */

use training\modules\admin\models\Lesson;

?>

<?= $form->field($model, 'active')->switch(); ?>
<?= $form->field($model, 'lesson_uuid')->dropDownList(Lesson::getList($model->lesson->course_uuid)); ?>
<?= $form->field($model, 'title')->textarea(); ?>
