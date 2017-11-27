<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\FormResult $model
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', ['model' => $model, 'workflow' => $workflow, 'title' => 'Edit result']); ?>