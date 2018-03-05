<?php
/**
 * @var \yii\web\View $this
 * @var \forms\modules\admin\models\FormStatus $model
 * @var \app\widgets\form\ActiveForm $form
 */

use mail\models\Template;

?>

<?= $form->field($model, 'sort'); ?>
<?= $form->field($model, 'mail_template_uuid')->dropDownList(Template::getList($model->form->event)); ?>
