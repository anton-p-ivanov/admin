<?php

namespace app\widgets\grid;

use yii\base\InvalidConfigException;
use yii\grid\Column;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class CheckboxColumn
 * @package app\widgets\grid
 */
class CheckboxColumn extends \yii\grid\CheckboxColumn
{
    /**
     * @var \yii\web\View
     */
    private $_view;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException if [[name]] is not set.
     */
    public function init()
    {
        Column::init();

        if (empty($this->name)) {
            throw new InvalidConfigException('The "name" property must be set.');
        }
        if (substr_compare($this->name, '[]', -2, 2)) {
            $this->name .= '[]';
        }

        $this->registerClientScript();
    }

    /**
     * Returns the view object that can be used to render views or view files.
     * The [[render()]] and [[renderFile()]] methods will use
     * this view object to implement the actual view rendering.
     * If not set, it will default to the "view" application component.
     * @return \yii\web\View the view object that can be used to render views or view files.
     */
    public function getView()
    {
        if ($this->_view === null) {
            $this->_view = \Yii::$app->getView();
        }

        return $this->_view;
    }

    /**
     * Sets the view object to be used by this widget.
     * @param \yii\web\View $view the view object that can be used to render views or view files.
     */
    public function setView($view)
    {
        $this->_view = $view;
    }

    /**
     * @inheritdoc
     */
    protected function renderHeaderCellContent()
    {
        if (!$this->multiple) {
            return Html::checkbox($this->getHeaderCheckBoxName(), false, ['disabled' => true]);
        }

        return parent::renderHeaderCellContent();
    }

    /**
     * Registers the needed JavaScript
     * @since 2.0.8
     */
    public function registerClientScript()
    {
        CheckboxColumnAsset::register($this->getView());

        $id = $this->grid->options['id'];
        $options = Json::encode([
            'name' => $this->name,
            'multiple' => $this->multiple,
            'checkAll' => $this->grid->showHeader ? $this->getHeaderCheckBoxName() : null,
        ]);

        $this->getView()->registerJs("jQuery('#$id').CheckboxColumn($options);");
    }
}
