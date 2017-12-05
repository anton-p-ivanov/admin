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

        // Registering assets
        DropDownInputAsset::register($this->view);
    }

    /**
     * @return string
     */
    public function renderInput()
    {
        $value = Html::getAttributeValue($this->model, $this->attribute);
        $options = ArrayHelper::merge($this->inputOptions, [
            'readonly' => !isset($this->options['data-type-ahead']),
            'value' => array_key_exists($value, $this->items) ? $this->items[$value] : null
        ]);

        $options = ArrayHelper::merge($options, $this->options);

        $hiddenOptions = ['value' => $value];
        if (isset($this->options['hiddenInputOptions'])) {
            $hiddenOptions = ArrayHelper::merge($hiddenOptions, $this->options['hiddenInputOptions']);
        }

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

        return Html::ul($this->items, ['class' => 'dropdown dropdown_wide', 'encode' => false]);
    }

    /**
     * @param $value
     * @param $key
     */
    protected function renderDropdownItem(&$value, $key)
    {
        $value = Html::tag('li', Html::a($value, '#', ['data-value' => $key]), [
            'class' => Html::getAttributeValue($this->model, $this->attribute) == $key ? 'active' : null
        ]);
    }
}