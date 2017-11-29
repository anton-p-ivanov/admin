<?php
/**
 * @var \yii\web\View $this
 * @var \mail\models\Template $model
 * @var \app\widgets\form\ActiveForm $form
 * @var \app\models\Workflow $workflow
 */

use app\models\Site;

?>

<?= $form->field($model, 'sites')->checkboxList(Site::getList()); ?>
<?= $form->field($model, 'code'); ?>

