<?php
namespace app\widgets\form;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class ActiveField
 * @package app\widgets\form
 */
class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * @var bool
     */
    public $inline = false;
    /**
     * @var array
     */
    public $options = ['class' => 'form-group'];
    /**
     * @var string
     */
    public $template = "{label}\n{input}\n{hint}\n{error}\n{actions}";
    /**
     * @var array
     */
    public $inputOptions = ['class' => 'form-group__input'];
    /**
     * @var array
     */
    public $labelOptions = ['class' => 'form-group__label'];
    /**
     * @var array
     */
    public $errorOptions = ['class' => 'form-group__error'];
    /**
     * @var array
     */
    public $hintOptions = ['class' => 'form-group__hint'];
    /**
     * @var array
     */
    public $actionButtonOptions = ['class' => 'form-group__action'];

    /**
     * @inheritdoc
     */
    public function render($content = null)
    {
        if ($content === null) {
            if (!isset($this->parts['{input}'])) {
                $this->textInput();
            }
            if (!isset($this->parts['{label}'])) {
                $this->label();
            }
            if (!isset($this->parts['{error}'])) {
                $this->error();
            }
            if (!isset($this->parts['{hint}'])) {
                $this->hint(null);
            }
            if (!isset($this->parts['{actions}'])) {
                $this->parts['{actions}'] = '';
            }
            $content = strtr($this->template, $this->parts);
        } elseif (!is_string($content)) {
            $content = call_user_func($content, $this);
        }

        return $this->begin() . "\n" . $content . "\n" . $this->end();
    }
    /**
     * @inheritdoc
     */
    public function dropDownList($items, $options = [])
    {
        $this->options['class'] .= ' form-group_dropdown';
        return $this->widget(DropdownInput::className(), ['items' => $items, 'options' => $options]);
    }

    /**
     * @inheritdoc
     */
    public function checkbox($options = [], $enclosedByLabel = true)
    {
        $this->options['class'] .= ' form-group_checkbox';
        return parent::checkbox($options, $enclosedByLabel);
    }

    /**
     * @inheritdoc
     */
    public function radioList($items, $options = [])
    {
        $this->options['class'] .= ' form-group_list';
        if ($this->inline) {
            $this->options['class'] .= ' form-group_inline';
        }

        $options['class'] = 'list-group';

        return parent::radioList($items, $options);
    }

    /**
     * @inheritdoc
     */
    public function checkboxList($items, $options = [])
    {
        $this->options['class'] .= ' form-group_list';
        if ($this->inline) {
            $this->options['class'] .= ' form-group_inline';
        }

        $options['class'] = 'list-group';

        return parent::checkboxList($items, $options);
    }

    /**
     * @inheritdoc
     */
    public function switch($options = [], $enclosedByLabel = true)
    {
        $this->options['class'] .= ' form-group_switch';
        return parent::checkbox($options, $enclosedByLabel);
    }

    /**
     * @inheritdoc
     */
    public function textInput($options = [])
    {
//        $options['autocomplete'] = 'off';
        if (empty($options['placeholder'])) {
            $options['placeholder'] = 'Not set';
        }

        return parent::textInput($options);
    }

    /**
     * @inheritdoc
     */
    public function textarea($options = [])
    {
        $this->options['class'] .= ' form-group_text';
        return parent::textarea($options);
    }

    /**
     * @return $this
     */
    public function cleanButton()
    {
        $this->parts['{actions}'] = Html::button('<i class="material-icons">close</i>', [
            'class' => 'form-group__action',
            'data-toggle' => 'clean'
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    public function multilineInput()
    {
        $editableArea = Html::tag('div', $this->model->{$this->attribute}, [
            'class' => 'form-group__input form-group__input_multiline',
            'data-editable' => 'true',
            'data-target' => '#' . Html::getInputId($this->model, $this->attribute)
        ]);

        $hiddenInput = Html::activeHiddenInput($this->model, $this->attribute);

        $this->parts['{input}'] = $editableArea . $hiddenInput;

        return $this;
    }

    /**
     * @param array $ranges
     * @param array $options
     * @return $this
     */
    public function rangeInput($ranges, $options = [])
    {
        $inputs = [];

        foreach ($ranges as $name) {
            $field = new self([
                'model' => $this->model,
                'attribute' => $this->attribute . "[$name]",
                'form' => $this->form
            ]);

            $defaultOptions = ['value' => $this->model->$name];

            $field
                ->cleanButton()
                ->textInput(ArrayHelper::merge($defaultOptions, $options))
                ->label($this->model->getAttributeLabel($name))
                ->hint($this->model->getAttributeHint($name))
                ->error();

            $inputs[] = Html::tag('div', $field->render(), ['class' => 'form-group__range-item']);
        }

        $rangeInput = Html::tag('div', implode("\n", $inputs), ['class' => 'form-group__range']);

        // Hidden fields is required for correct form field javascript behavior
//        $hiddenInput = Html::activeHiddenInput($this->model, $this->attribute, ['value' => false]);

        $this->label(false);
        $this->parts['{input}'] = /*$hiddenInput . */$rangeInput;

        return $this;
    }

    /**
     * @return $this
     * @todo does not used anywhere yet
     */
    public function json()
    {
        $rangeInputs = [];
        $ranges = ['label', 'value'];

        for ($counter = 0; $counter < 2; $counter++) {
            $inputs = [];
            foreach ($ranges as $name) {
                $field = new self([
                    'model' => $this->model,
                    'attribute' => $this->attribute . "[$name][$counter]",
                    'form' => $this->form
                ]);

                $field
                    ->cleanButton()
                    ->textInput([/*'value' => $this->model->$name, */'placeholder' => ucfirst($name)])
                    ->label(false)
                    ->hint(false)
                    ->error(false);

                $inputs[] = Html::tag('div', $field->render(), ['class' => 'form-group__json-item']);
            }

            $rangeInputs[] = Html::tag('div', implode("\n", $inputs), ['class' => 'form-group__json form-group__json-' . $counter]);
        }

        $this->parts['{input}'] = implode('', $rangeInputs);

        return $this;
    }
}