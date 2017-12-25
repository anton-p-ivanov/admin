<?php
/**
 * @var string $account_uuid
 */
?>
<p>There are no contacts yet. You can add a new one by clicking a button below.</p>
<p><?= \yii\helpers\Html::a('Add new contact', ['contacts/create', 'account_uuid' => $account_uuid], [
        'class' => 'btn btn_primary',
        'data-toggle' => 'modal',
        'data-target' => '#contacts-modal',
        'data-reload' => 'true',
        'data-pjax' => 'false',
        'data-persistent' => 'true'
    ]); ?></p>
