<?php

namespace app\widgets;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class Tabs
 * @package app\widgets
 */
class Tabs extends Widget
{
    /**
     * An array of tabs
     * @var array
     */
    public $items;
    /**
     * Default tab item structure.
     * @var array
     */
    private $_defaultItem = [
        'title' => 'Tab',
        'url' => '#',
        'visible' => true,
        'active' => false,
        'options' => [],
    ];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        // Normalize buttons
        $this->normalizeItems();

        // Register assets
        TabsAsset::register($this->view);

        if (!$this->items) {
            throw new InvalidConfigException('At least one tab must be visible.');
        }

        echo Html::beginTag('div', ['class' => 'tabs']);

        // Display tabs navigation only when more than one tab is available
        if (count($this->items) > 1) {
            echo $this->renderNav();
        }

        echo Html::beginTag('div', ['class' => 'tabs-content']);
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        echo Html::endTag('div'); // Closes `.tabs-content` container
        echo Html::endTag('div'); // Closes `.tabs` container
    }

    /**
     * Render tabs navigation area.
     */
    protected function renderNav()
    {
        $nav = [];

        foreach ($this->items as $index => $item) {
            $options = array_merge([
                'class' => 'tabs-nav__link' . ($item['active'] === true ? ' active' : ''),
                'data-toggle' => 'tab',
                'data-target' => '#' . $item['id'],
            ], $item['options']);

            $nav[] = Html::a($item['title'], $item['url'], $options);
        }

        return Html::ul($nav, [
            'encode' => false,
            'class' => 'tabs-nav',
            'itemOptions' => ['class' => 'tabs-nav__item']
        ]);
    }

    /**
     * Normalize items.
     */
    protected function normalizeItems()
    {
        // Filter visible only items
        $this->items = array_filter($this->items, function ($item) {
            return !isset($item['visible']) || $item['visible'] === true;
        });

        array_walk($this->items, [$this, 'normalizeItem']);
    }

    /**
     * @param $item
     * @param $index
     * @throws InvalidConfigException
     */
    protected function normalizeItem(&$item, $index)
    {
        if (!is_array($item)) {
            throw new InvalidConfigException('Tabs item must be an array.');
        }

        $item = ArrayHelper::merge($this->_defaultItem, $item);

        if (!isset($item['id'])) {
            $item['id'] = 'tab_' . $index;
        }
    }
}