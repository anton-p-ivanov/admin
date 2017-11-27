<?php
/**
 * @var \yii\web\View $this
 * @var \forms\modules\fields\models\Field $model
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', ['model' => $model, 'workflow' => $workflow, 'title' => 'New field']); ?>