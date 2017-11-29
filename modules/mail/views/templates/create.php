<?php
/**
 * @var \yii\web\View $this
 * @var \mail\models\Template $model
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', ['model' => $model, 'workflow' => $workflow, 'title' => 'New template']); ?>