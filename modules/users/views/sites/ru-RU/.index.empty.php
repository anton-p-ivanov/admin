<?php
/**
 * @var string $user_uuid
 */
?>
<p>Связанных сайтов не найдено. Вы можете добавить новый сайт, нажав на кнопку ниже.</p>
<p><?= \yii\helpers\Html::a('Добавить сайт', ['sites/create', 'user_uuid' => $user_uuid], [
        'class' => 'btn btn_primary',
        'data-toggle' => 'modal',
        'data-target' => '#access-sites-modal',
        'data-reload' => 'true',
        'data-pjax' => 'false',
        'data-persistent' => 'true'
    ]); ?></p>
