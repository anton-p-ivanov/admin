<?php
/**
 * @var \yii\web\View $this
 * @var \fields\models\Field $model
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('@fields/views/fields/.form.php', [
    'model' => $model,
    'workflow' => $workflow,
    'title' => 'New field'
]); ?>