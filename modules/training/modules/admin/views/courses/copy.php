<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Course $model
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', ['model' => $model, 'title' => 'Copy course', 'workflow' => $workflow]); ?>