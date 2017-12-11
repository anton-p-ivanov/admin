<?php
/**
 * @var \yii\web\View $this
 * @var \users\models\User $model
 * @var \users\models\UserAccount $account
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', [
    'model' => $model,
    'account' => $account,
    'workflow' => $workflow,
    'title' => 'New user'
]); ?>