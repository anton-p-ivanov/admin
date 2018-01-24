<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Question $model
 * @var string $course_uuid
 */
?>

<?= $this->render('.form.php', ['model' => $model, 'title' => 'Edit question', 'course_uuid' => $course_uuid]); ?>