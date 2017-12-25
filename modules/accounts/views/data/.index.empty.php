<?php
/**
 * @var string $updateUrl
 */
?>

<p>There are no custom fields yet. Add at least one <a href="<?= \yii\helpers\Url::to(['fields/fields/index']); ?>" target="_blank" data-pjax="false">custom field</a> and refresh view.</p>
<p><?= \yii\helpers\Html::a('Refresh', $updateUrl, [
        'class' => 'btn btn_primary',
    ]); ?></p>
