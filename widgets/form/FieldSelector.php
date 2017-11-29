<?php
namespace app\widgets\form;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class FieldSelector
 *
 * @package app\widgets\form
 */
class FieldSelector extends Widget
{
    /**
     * @var \yii\widgets\ActiveForm
     */
    public $form;
    /**
     * @var \yii\db\ActiveRecord
     */
    public $model;
    /**
     * @var array
     */
    public $attributes;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Register assets
        FieldSelectorAsset::register($this->view);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::beginTag('div', ['class' => 'field-selector']);
        foreach ($this->attributes as $index => $attribute) {
            echo $this->form->field($this->model, $attribute, ['options' => [
                'class' => 'form-group' . ($index > 0 ? ' form-group_hidden' : '')
            ]]);
        }

        $this->renderDropdown();
        echo Html::endTag('div');
    }

    /**
     * Render dropdown toggle and menu
     */
    protected function renderDropdown()
    {
        echo Html::a('<i class="material-icons">arrow_drop_down</i>', '#', [
            'class' => 'form-group__action',
            'data-toggle' => 'dropdown'
        ]);

        echo Html::beginTag('ul', ['class' => 'dropdown dropdown_right']);
        foreach ($this->attributes as $attribute) {
            echo Html::beginTag('li');
            echo Html::a($this->model->getAttributeLabel($attribute), '#', [
                'data-toggle' => 'field-selector',
                'data-target' => Html::getInputId($this->model, $attribute)
            ]);
            echo Html::endTag('li');
        }
        echo Html::endTag('ul');
    }
}
