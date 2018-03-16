<?php
namespace app\widgets\form;

use fields\models\Field;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Class FormInput
 *
 * @package app\widgets\form
 */
class FormInput extends InputWidget
{
    /**
     * @var Field
     */
    public $formField;
    /**
     * @var array
     */
    public $options = ['class' => 'form-group__input'];
    /**
     * @var array
     */
    public static $types = [
        Field::FIELD_TYPE_STRING => 'default',
        Field::FIELD_TYPE_TEXT => 'text',
        Field::FIELD_TYPE_SELECT => 'select',
        Field::FIELD_TYPE_LIST => 'list',
        Field::FIELD_TYPE_FILE => 'file'
    ];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $options = Json::decode($this->formField->options);
        if (is_array($options)) {
            $this->options = ArrayHelper::merge($this->options, $options);
        }

        $value = Html::getAttributeValue($this->model, $this->attribute);

        if (!$value && $this->formField->default) {
            $this->options['value'] = $this->formField->default;
        }

        if (array_key_exists($this->formField->type, self::$types)) {
            echo call_user_func([$this, 'render' . ucfirst(self::$types[$this->formField->type]) . 'Input']);
        }
    }

    /**
     * @return string
     */
    protected function renderDefaultInput()
    {
        return Html::activeTextInput($this->model, $this->attribute, $this->options);
    }

    /**
     * @return string
     */
    protected function renderTextInput()
    {
        $this->field->options['class'] .= ' form-group_text';
        $this->options['data-toggle'] = 'editor';
        return Html::activeTextarea($this->model, $this->attribute, $this->options);
    }

    /**
     * @return string
     */
    protected function renderSelectInput()
    {
        $values = ArrayHelper::map($this->formField->fieldValues, 'value', 'label');
        $this->field->options['class'] .= ' form-group_list';
        $this->options['class'] = 'list-group';

        if (!$values) {
            return Html::tag('div', \Yii::t('widgets/FormInput', 'There is no field values to display.'), [
                'class' => 'alert alert_warning'
            ]);
        }

        if ($this->formField->multiple) {
            return Html::activeCheckboxList($this->model, $this->attribute, $values, $this->options);
        }
        else {
            return Html::activeRadioList($this->model, $this->attribute, $values, $this->options);
        }
    }

    /**
     * @return string
     */
    protected function renderListInput()
    {
        $values = ArrayHelper::map($this->formField->fieldValues, 'value', 'label');
        if (!$values) {
            return Html::tag('div', \Yii::t('widgets/FormInput', 'There is no field values to display.'), [
                'class' => 'alert alert_warning'
            ]);
        }

        $this->field->parts['{actions}'] = null;
        $this->field->options['class'] .= ' form-group_dropdown';

        if (!$this->model->{$this->attribute}) {
            $this->model->{$this->attribute} = $this->formField->default;
        }

        return DropdownInput::widget([
            'model' => $this->model,
            'attribute' => $this->attribute,
            'items' => $values,
        ]);
    }

    /**
     * @return string
     */
    protected function renderFileInput()
    {
        return File::widget([
            'model' => $this->model,
            'attribute' => $this->attribute,
        ]);
    }
}
