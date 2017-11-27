<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

/**
 * Class ToolbarButtons
 * @package app\widgets
 */
class ToolbarButtons extends Widget
{
    /**
     * @var array|callable
     */
    public $items = [];
    /**
     * @var array
     */
    public $options = [];
    /**
     * @var bool
     */
    public $encodeLabels = true;

    /**
     * @return string
     */
    public function run()
    {
        $this->normalizeItems();

        $result = '';

        foreach ($this->items as $group) {
            $result .= '<div class="btn-group" role="group">' . $this->renderButtons($group) . '</div>';
        }

        return $result;
    }

    /**
     * Normalize items
     */
    protected function normalizeItems()
    {
        $this->items = array_map(function ($items) {

            // Filter visible only items
            $items = array_filter($items, function ($item) {
                return !isset($item['visible']) || $item['visible'] === true;
            });

            $defaultItem = [
                'label' => '',
                'encode' => true,
                'url' => '',
                'items' => [],
                'options' => [
                    'class' => 'btn btn_default'
                ]
            ];

            $items = array_map(function ($item) use ($defaultItem) {
                // Fit item structure
                $item = ArrayHelper::merge($defaultItem, $item);

                // Encode labels
                if ($this->encodeLabels && $item['encode']) {
                    $item['label'] = Html::encode($item['label']);
                }

                return $item;
            }, $items);

            return $items;

        }, $this->items);
    }

    /**
     * @param $group
     * @return string
     */
    protected function renderButtons($group)
    {
        $result = '';

        foreach ($group as $btn) {
            if ($btn['url']) {
                $result .= Html::a($btn['label'], Url::to($btn['url']), $btn['options']);
            }
            else {
                if ($btn['items']) {
                    $options = ArrayHelper::merge($btn['options'], [
                        'data-toggle' => 'dropdown'
                    ]);
                    $result .= Html::a($btn['label'], '#', $options);
                    $result .= Menu::widget(['items' => $btn['items'], 'options' => ['class' => 'dropdown']]);
                }
                else {
                    $result .= Html::tag('span', $btn['label'], $btn['options']);
                }
            }
        }

        return $result;
    }


}