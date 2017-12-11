<?php
/**
 * @var \yii\web\View $this
 * @var \users\models\User $model
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', ['model' => $model, 'workflow' => $workflow, 'title' => 'Edit user']); ?>