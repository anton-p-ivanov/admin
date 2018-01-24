<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Lesson $model
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', ['model' => $model, 'title' => 'New lesson', 'workflow' => $workflow]); ?>