<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\Result $model
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', ['model' => $model, 'workflow' => $workflow, 'title' => 'New result']); ?>