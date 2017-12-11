<?php
/**
 * @var string $user_uuid
 */
?>

<p>Связанных аккаунтов не найдено. Вы можете добавить новую связь с аккаунтом, нажав на кнопку ниже.</p>
<p><?= \yii\helpers\Html::a('Связать с аккаунтом', ['accounts/create', 'user_uuid' => $user_uuid], [
        'class' => 'btn btn_primary',
        'data-toggle' => 'modal',
        'data-target' => '#accounts-modal',
        'data-reload' => 'true',
        'data-pjax' => 'false',
        'data-persistent' => 'true'
    ]); ?></p>