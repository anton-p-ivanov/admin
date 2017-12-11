<?php
/**
 * @var string $updateUrl
 */
?>

<p>There are no user fields yet. Add at least one <a href="<?= \yii\helpers\Url::to(['fields/fields/index']); ?>" target="_blank" data-pjax="false">user field</a> and refresh view.</p>
<p><?= \yii\helpers\Html::a('Refresh', $updateUrl, [
        'class' => 'btn btn_primary',
    ]); ?></p>
