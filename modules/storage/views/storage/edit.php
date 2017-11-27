<?php
/**
 * @var \yii\web\View $this
 * @var string $title
 * @var storage\models\Storage $model
 */
?>

<?= $this->render('.form.php', [
    'model' => $model,
    'title' => $model->isDirectory() ? 'Изменение атрибутов папки' : 'Изменение атрибутов файла'
]); ?>