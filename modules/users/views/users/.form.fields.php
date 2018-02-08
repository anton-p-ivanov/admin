<?php
/**
 * @var \yii\web\View $this
 * @var \users\models\User $model
 * @var \users\models\UserPassword $password
 * @var \users\models\UserData $data
 * @var \app\widgets\form\ActiveForm $form
 */

use users\modules\admin\modules\fields\models\Field;

?>

<?php foreach (Field::getList() as $field): ?>
    <?php
    $f = $form->field($model, 'data[' . $field->uuid . ']');
    if ($field->isRequired()) {
        $f->options['class'] .= ' required';
    }
    ?>
    <?= $f
        ->label($field->label)
        ->hint($field->description)
        ->cleanButton()
        ->widget(\app\widgets\form\FormInput::className(), ['formField' => $field]); ?>

<?php endforeach; ?>