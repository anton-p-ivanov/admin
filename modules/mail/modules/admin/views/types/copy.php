<?php
/**
 * @var \yii\web\View $this
 * @var \mail\models\Type $model
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', ['model' => $model, 'workflow' => $workflow, 'title' => 'Copy type']); ?>