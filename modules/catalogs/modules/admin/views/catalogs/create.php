<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\modules\admin\models\Catalog $model
 * @var \app\models\Workflow $workflow
 */
?>

<?= $this->render('.form.php', ['model' => $model, 'workflow' => $workflow, 'title' => 'New catalog']); ?>