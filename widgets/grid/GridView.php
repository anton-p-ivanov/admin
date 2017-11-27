<?php

namespace app\widgets\grid;

use yii\widgets\BaseListView;

/**
 * Class GridView
 * @package app\widgets\grid
 */
class GridView extends \yii\grid\GridView
{
    /**
     * @var array
     */
    public $path;
    /**
     * @var string
     */
    public $layout = '{items}{pager}';
    /**
     * @var array
     */
    public $tableOptions = ['class' => 'grid-view__table'];
    /**
     * @var array
     */
    public $pager = [
        'class' => 'app\widgets\grid\Pager',
    ];
    /**
     * @var bool
     */
    public $enableClientScript = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Register widget i18n-messages
        $this->registerTranslations();

        if ($this->enableClientScript) {
            // Register asset bundle
            GridViewAsset::register($this->view);
        }
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        BaseListView::run();
    }

    /**
     * Register translation messages.
     */
    public function registerTranslations()
    {
        \Yii::$app->i18n->translations['widgets*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/widgets/grid/messages',
            'fileMap' => [
                'widgets/grid/GridView' => 'GridView.php'
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
        return \Yii::t('widgets/grid/GridView', $message, $params, $language);
    }
}