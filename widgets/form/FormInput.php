<?php
namespace app\widgets\form;

use forms\modules\fields\models\Field;
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

        if ($this->formField->multiple) {
            return Html::activeCheckboxList($this->model, $this->attribute, $values, $this->options);
        }
        else {
            return Html::activeRadioList($this->model, $this->attribute, $values, $this->options);
        }
    }

    /**
     * @return string
     * @todo: redesign of multiple field needed
     */
    protected function renderListInput()
    {
        $values = ArrayHelper::map($this->formField->fieldValues, 'value', 'label');
        if ($this->formField->multiple) {
            $this->options['multiple'] = true;
            return Html::activeListBox($this->model, $this->attribute, $values, $this->options);
        }
        else {
            $this->field->options['class'] .= ' form-group_dropdown';
            return DropdownInput::widget([
                'model' => $this->model,
                'attribute' => $this->attribute,
                'items' => $values
            ]);
        }
    }

    /**
     * @return string
     * @todo: redesign needed
     */
    protected function renderFileInput()
    {
        return Html::activeTextInput($this->model, $this->attribute, $this->options);
    }
}
