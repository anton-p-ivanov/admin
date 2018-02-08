<?php
namespace app\widgets\form;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class DropdownInput
 * @package app\widgets\form
 */
class DropdownInput extends BaseInput
{
    /**
     * Array of dropdown items (value => text)
     * @var array
     */
    public $items = [];
    /**
     * @var array
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Normalizing widget options
        $this->normalizeOptions();

        // Registering assets
        DropDownInputAsset::register($this->view);
    }

    /**
     * Normalizing widget options.
     */
    protected function normalizeOptions()
    {
        $defaultOptions = [
            'data-type-ahead' => false,
            'hiddenInputOptions' => [],
            'multiple' => false
        ];

        $this->options = ArrayHelper::merge($defaultOptions, $this->options);
    }

    /**
     * @return string
     */
    public function renderInput()
    {
        $value = Html::getAttributeValue($this->model, $this->attribute);
        $options = ArrayHelper::merge($this->inputOptions, [
            'readonly' => $this->options['data-type-ahead'] === true,
            'autocomplete' => 'off',
            'value' => array_key_exists($value, $this->items) ? strip_tags($this->items[$value]) : null
        ]);

        $options = ArrayHelper::merge($options, $this->options);

        $hiddenOptions = ['value' => $value, 'id' => false];
        if ($this->options['hiddenInputOptions']) {
            $hiddenOptions = ArrayHelper::merge($hiddenOptions, $this->options['hiddenInputOptions']);
        }

        unset($options['hiddenInputOptions']);

        return
            Html::activeTextInput($this->model, $this->attribute, $options) .
            Html::activeHiddenInput($this->model, $this->attribute, $hiddenOptions);
    }

    /**
     * @return string
     */
    public function renderActionButtons()
    {
        $dropDownButton = Html::button('<i class="material-icons">arrow_drop_down</i>', [
            'class' => 'form-group__action',
            'encode' => false,
            'data-toggle' => 'dropdown'
        ]);

        $clearButton = Html::button('<i class="material-icons">close</i>', [
            'class' => 'form-group__action form-group__action_clean',
            'encode' => false,
            'data-toggle' => 'clean'
        ]);

        return $clearButton . $dropDownButton . $this->renderDropdown();
    }

    /**
     * @return string
     */
    protected function renderDropdown()
    {
        array_walk($this->items, [$this, 'renderDropdownItem']);

        $classNames = ['dropdown', 'dropdown_wide'];

        if (isset($this->options['dropdown']['class'])) {
            $classNames = explode(' ', $this->options['dropdown']['class']);
        }

        if ($this->options['multiple']) {
            $classNames[] = 'dropdown_checkboxes';
        }

        return Html::ul($this->items, ['class' => implode(' ', $classNames), 'encode' => false]);
    }

    /**
     * @param $value
     * @param $key
     */
    protected function renderDropdownItem(&$value, $key)
    {
        if ($this->options['multiple']) {
            $content = Html::activeCheckbox($this->model, $this->attribute, [
                'label' => $value,
                'value' => $key,
                'data-value' => $key,
                'uncheck' => false
            ]);
        }
        else {
            $content = Html::a($value, '#', ['data-value' => $key]);
        }

        $value = Html::tag('li', $content, [
            'class' => Html::getAttributeValue($this->model, $this->attribute) == $key ? 'active' : null
        ]);
    }
}