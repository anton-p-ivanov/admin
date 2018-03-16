<?php
/**
 * @var $this \yii\web\View
 * @var $file \storage\models\StorageFile
 * @var $widget \app\widgets\form\File
 */

use yii\helpers\Html;

$widget = $this->context;
?>

<div class="file-info<?php if ($file === null): ?> file-info_empty<?php endif; ?>">
<?php if ($file): ?>
    <?= $this->render('File.Info.php', ['file' => $file]); ?>
<?php else: ?>
    <em class="text_center">No file selected. Click "Select" button<br>to select file from library.</em>
<?php endif; ?>
</div>

<?= Html::activeHiddenInput($widget->model, $widget->attribute); ?>