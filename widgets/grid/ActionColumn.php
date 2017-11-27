<?php

namespace app\widgets\grid;

use yii\grid\Column;
use yii\widgets\Menu;

/**
 * Class ActionColumn
 * @package app\widgets\grid
 */
class ActionColumn extends Column
{
    /**
     * @var array|\Closure
     */
    public $items = [];
    /**
     * @var string
     */
    public $header = '<i class="material-icons">more_vert</i>';
    /**
     * @var array
     */
    public $contentOptions = ['class' => 'action-column'];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        ActionColumnAsset::register(\Yii::$app->controller->view);
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $items = $this->items;

        if ($this->items instanceof \Closure) {
            $items = call_user_func($this->items, $model, $key, $index);
        }

        return
            '<div class="action-column__container">'
            . '<div class="action-column__toggle" data-toggle="dropdown" title="Click here to open context menu"></div>'
            . Menu::widget(['items' => $items, 'options' => ['class' => 'dropdown']])
            . '</div>';
    }
}
