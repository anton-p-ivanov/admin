<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\models\Account $model
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', ['model' => $model, 'workflow' => $workflow, 'title' => 'Copy account']); ?>