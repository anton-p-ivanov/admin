<?php

namespace app\widgets;

use yii\base\Widget;

/**
 * Class ToolbarSearch
 * @package app\widgets
 */
class ToolbarSearch extends Widget
{
    /**
     * @var array|string
     */
    public $searchRoute = ['search'];
    /**
     * @var array|string
     */
    public $filterRoute = ['filter'];
    /**
     * @var array|string
     */
    public $resetRoute = ['index'];
    /**
     * @var bool
     */
    public $filterEnabled = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->filterRoute['filter'] = \Yii::$app->request->get('filter');
        $this->registerTranslations();
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('ToolbarSearch');
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
                'widgets/ToolbarSearch' => 'ToolbarSearch.php'
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
        return \Yii::t('widgets/ToolbarSearch', $message, $params, $language);
    }
}