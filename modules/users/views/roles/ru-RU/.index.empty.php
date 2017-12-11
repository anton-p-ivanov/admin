<?php
/**
 * @var string $user_uuid
 */
?>

<p>Связанных ролей не найдено. Вы можете добавить новую роль, нажав на кнопку ниже.</p>
<p><?= \yii\helpers\Html::a('Добавить роль', ['roles/create', 'user_uuid' => $user_uuid], [
        'class' => 'btn btn_primary',
        'data-toggle' => 'modal',
        'data-target' => '#access-roles-modal',
        'data-reload' => 'true',
        'data-pjax' => 'false',
        'data-persistent' => 'true'
    ]); ?></p>
