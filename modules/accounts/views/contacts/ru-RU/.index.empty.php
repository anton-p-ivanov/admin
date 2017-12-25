<?php
/**
 * @var string $account_uuid
 */
?>
<p>Контакты не найдены. Вы можете добавить контакт, нажав на кнопку ниже.</p>
<p><?= \yii\helpers\Html::a('Добавить контакт', ['contacts/create', 'account_uuid' => $account_uuid], [
        'class' => 'btn btn_primary',
        'data-toggle' => 'modal',
        'data-target' => '#contacts-modal',
        'data-reload' => 'true',
        'data-pjax' => 'false',
        'data-persistent' => 'true'
    ]); ?></p>
