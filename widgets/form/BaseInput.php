<?php
namespace app\widgets\form;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Class BaseInput
 * @package app\widgets\form
 */
abstract class BaseInput extends InputWidget
{
    /**
     * @var array
     */
    public $options = ['class' => 'form-group'];
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
    public function run()
    {
        parent::run();

        echo $this->renderInput();
        echo $this->renderActionButtons();
    }

    /**
     * @return string
     */
    abstract public function renderInput();

    /**
     * @return string
     */
    public function renderActionButtons()
    {
        $options = ArrayHelper::merge($this->actionButtonOptions, ['data-toggle' => 'clean']);
        return Html::buttonInput('<i class="material-icons">clear</i>', $options);
    }
}