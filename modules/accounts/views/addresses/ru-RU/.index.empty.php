<?php
/**
 * @var string $account_uuid
 */
?>
<p>Адреса не найдены. Вы можете добавить адрес, нажав на кнопку ниже.</p>
<p><?= \yii\helpers\Html::a('Добавить адрес', ['addresses/create', 'account_uuid' => $account_uuid], [
        'class' => 'btn btn_primary',
        'data-toggle' => 'modal',
        'data-target' => '#addresses-modal',
        'data-reload' => 'true',
        'data-pjax' => 'false',
        'data-persistent' => 'true'
    ]); ?></p>
