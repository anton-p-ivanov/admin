<?php
/**
 * @var \yii\web\View $this
 * @var \mail\models\Template $model
 * @var \app\widgets\form\ActiveForm $form
 */
?>
<?/*= $form->field($model, 'format', ['inline' => true])->radioList([
    'textBody' => $model->getAttributeLabel('textBody'),
    'htmlBody' => $model->getAttributeLabel('htmlBody'),
]); */?>
<ul id="template-format">
    <?php foreach (['textBody', 'htmlBody'] as $format): ?>
        <li<?= $model->format === $format ? ' class="active"' : ''; ?>><a href="#" data-value="<?= $format; ?>"><?= $model->getAttributeLabel($format); ?></a></li>
    <?php endforeach; ?>
</ul>
<?php foreach (['textBody', 'htmlBody'] as $format): ?>
    <?php $options = ['class' => 'form-group form-group_text']; ?>
    <?php if ($model->format !== $format) {
        $options['class'] .= ' form-group_hidden';
    } ?>
    <?= $form->field($model, $format, ['options' => $options])->textarea(['data-toggle' => 'editor'])->label(false); ?>
<?php endforeach; ?>
