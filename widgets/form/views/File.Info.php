<?php
/**
 * @var $this \yii\web\View
 * @var $file \storage\models\StorageFile
 */
?>

<div class="file-info__icon">
    <a href="<?= \yii\helpers\Url::to(['/storage/storage/download', 'uuid' => $file->uuid]); ?>" data-file="<?= $file->uuid; ?>">
        <i class="material-icons">file_download</i>
    </a>
</div>
<div class="file-info__content">
    <b data-file="name"><?= $file->name; ?></b><br>
    Size: <span data-file="size"><?= Yii::$app->formatter->asShortSize($file->size, 2); ?></span><br>
    MD5: <span data-file="hash"><?= $file->hash; ?></span>

    <a href="#" class="file-info__clear" data-toggle="clear">
        <i class="material-icons">clear</i>
    </a>
</div>