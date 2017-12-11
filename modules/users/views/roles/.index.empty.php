<?php
/**
 * @var string $user_uuid
 */
?>

<p>There are no assigned roles yet. You can assign a new one by clicking a button below.</p>
<p><?= \yii\helpers\Html::a('Assign role', ['roles/create', 'user_uuid' => $user_uuid], [
        'class' => 'btn btn_primary',
        'data-toggle' => 'modal',
        'data-target' => '#access-roles-modal',
        'data-reload' => 'true',
        'data-pjax' => 'false',
        'data-persistent' => 'true'
    ]); ?></p>
