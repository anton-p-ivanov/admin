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
    public $options = ['class' => 'form-group form-group_dropdown'];

    /**
     * @return string
     */
    public function renderInput()
    {
        $value = Html::getAttributeValue($this->model, $this->attribute);
        $options = ArrayHelper::merge($this->inputOptions, [
            'readonly' => true,
            'value' => array_key_exists($value, $this->items) ? $this->items[$value] : null
        ]);

        return
            Html::activeTextInput($this->model, $this->attribute, $options) .
            Html::activeHiddenInput($this->model, $this->attribute, ['value' => $value]);
    }

    /**
     * @return string
     */
    public function renderActionButtons()
    {
        return Html::button('<i class="material-icons">arrow_drop_down</i>', [
            'class' => 'form-group__action',
            'encode' => false,
            'data-toggle' => 'dropdown'
        ]) . $this->renderDropdown();
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