<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $fields
 */
?>
<div class="modal__container">
    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('forms', 'List of form fields codes'); ?></div>
    </div>
    <div class="modal__body">
        <table class="fields-list text_small">
            <?php foreach ($fields as $code => $label): ?>
                <tbody>
                <tr>
                    <td><code>{{<?= mb_strtoupper($code); ?>}}</code></td>
                    <td><?= $label; ?></td>
                </tr>
                </tbody>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="modal__footer">
        <button class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
    </div>
</div>