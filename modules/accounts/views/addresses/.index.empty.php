<?php
/**
 * @var string $account_uuid
 */
?>
<p>There are no addresses yet. You can add a new one by clicking a button below.</p>
<p><?= \yii\helpers\Html::a('Add new address', ['addresses/create', 'account_uuid' => $account_uuid], [
        'class' => 'btn btn_primary',
        'data-toggle' => 'modal',
        'data-target' => '#addresses-modal',
        'data-reload' => 'true',
        'data-pjax' => 'false',
        'data-persistent' => 'true'
    ]); ?></p>
