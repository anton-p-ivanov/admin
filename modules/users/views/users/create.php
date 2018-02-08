<?php
/**
 * @var \yii\web\View $this
 * @var \users\models\User $model
 * @var \users\models\UserPassword $password
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', [
    'model' => $model,
    'password' => $password,
    'workflow' => $workflow,
    'title' => 'New user'
]); ?>