<?php
/**
 * @var string $user_uuid
 */
?>

<p>There are no account links yet. You can add a new one by clicking a button below.</p>
<p><?= \yii\helpers\Html::a('Link with account', ['accounts/create', 'user_uuid' => $user_uuid], [
        'class' => 'btn btn_primary',
        'data-toggle' => 'modal',
        'data-target' => '#accounts-modal',
        'data-reload' => 'true',
        'data-pjax' => 'false',
        'data-persistent' => 'true'
    ]); ?></p>