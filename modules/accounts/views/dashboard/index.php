<?php
/**
 * @var \yii\web\View $this
 */

$this->title = sprintf('%s — %s',
    Yii::t('app', 'Control panel'),
    Yii::t('accounts', \accounts\Module::$title)
);
?>

Accounts dashboard

<a href="<?= \yii\helpers\Url::to(['accounts/index']); ?>">Manage accounts</a>