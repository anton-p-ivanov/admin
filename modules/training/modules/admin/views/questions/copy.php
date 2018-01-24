<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Question $model
 * @var string $course_uuid
 */
?>

<?= $this->render('.form.php', ['model' => $model, 'title' => 'Copy question', 'course_uuid' => $course_uuid]); ?>