<?php
/**
 * @var string $updateUrl
 */
?>

<p>Не найдено ни одного пользовательского поля. Добавьте хотя бы одно <a href="<?= \yii\helpers\Url::to(['fields/fields/index']); ?>" target="_blank" data-pjax="false">поле</a>.</p>
<p><?= \yii\helpers\Html::a('Refresh', $updateUrl, [
        'class' => 'btn btn_primary',
    ]); ?></p>
