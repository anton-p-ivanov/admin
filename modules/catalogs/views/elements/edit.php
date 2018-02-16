<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\models\Element $model
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', [
    'model' => $model,
    'workflow' => $workflow,
    'title' => $model->isSection() ? 'Edit section' : 'Edit element'
]); ?>