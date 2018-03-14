<?php

namespace app\widgets;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

/**
 * Class Toolbar
 * @package app\widgets
 */
class Toolbar extends Widget
{
    /**
     * @var array
     */
    public $buttons;
    /**
     * @var bool
     */
    public $isFiltered = false;
    /**
     * @var array
     */
    public $options = ['class' => 'toolbar'];
    /**
     * @var bool
     */
    public $encodeLabels = true;
    /**
     * @var bool
     */
    public $enableClientScript = true;

    /**
     * Initializes the pager.
     */
    public function init()
    {
        parent::init();

        if ($this->buttons === null) {
            throw new InvalidConfigException('The "buttons" property must be set.');
        }

        // Normalize buttons
        $this->normalizeItems();

        // Register widget translations
        $this->registerTranslations();

        if ($this->enableClientScript) {
            // Register assets
            ToolbarAsset::register($this->view);
        }
    }

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run()
    {
        $selectedButtons = [];
        $filteredButtons = [];

        if (isset($this->buttons['selected'])) {
            $selectedButtons = $this->buttons['selected'];
            unset($this->buttons['selected']);
        }

        if (isset($this->buttons['filtered'])) {
            $filteredButtons = $this->buttons['filtered'];
            unset($this->buttons['filtered']);
        }

        echo $this->render('Toolbar', [
            'buttons' => $this->buttons,
            'selectedButtons' => $selectedButtons,
            'filteredButtons' => $filteredButtons
        ]);
    }

    /**
     * Normalize items
     */
    protected function normalizeItems()
    {
        $this->buttons = array_map(function ($items) {

            // Filter visible only items
            $items = array_filter($items, function ($item) {
                return !isset($item['visible']) || $item['visible'] === true;
            });

            $defaultItem = [
                'label' => '',
                'encode' => true,
                'url' => '',
                'items' => [],
                'options' => ['class' => 'toolbar-btn'],
                'menuOptions' => ['class' => 'dropdown']
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

        }, $this->buttons);
    }

    /**
     * @param $group
     * @return string
     */
    public function renderButtons($group)
    {
        $result = '';

        foreach ($group as $btn) {
            if ($btn['url']) {
                $result .= Html::a($btn['label'], Url::to($btn['url']), $btn['options']);
            }
            else {
                if ($btn['items']) {
                    $options = ArrayHelper::merge($btn['options'], ['data-toggle' => 'dropdown']);
                    $result .= Html::beginTag('div', ['class' => 'toolbar-btn__container']);
                    $result .= Html::a($btn['label'], '#', $options);
                    $result .= Menu::widget(['items' => $btn['items'], 'options' => $btn['menuOptions']]);
                    $result .= Html::endTag('div');
                }
                else {
                    $result .= Html::tag('span', $btn['label'], $btn['options']);
                }
            }
        }

        return $result;
    }

    /**
     * Register translation messages.
     */
    public function registerTranslations()
    {
        \Yii::$app->i18n->translations['widgets*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/widgets/messages',
            'fileMap' => [
                'widgets/Toolbar' => 'Toolbar.php'
            ]
        ];
    }

    /**
     * @param string $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($message, $params = [], $language = null)
    {
        return \Yii::t('widgets/Toolbar', $message, $params, $language);
    }
}