<?php

namespace training;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package training
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'dashboard';
    /**
     * @var string
     */
    public static $title = 'Training';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $config = require_once(__DIR__ . '/config/web.php');
        $env = require_once(__DIR__ . '/config/' . YII_ENV .'/web.php');

        // initialize the module with the configuration loaded from config file
        \Yii::configure($this, ArrayHelper::merge($config, $env));

        $this->registerTranslations();
    }

    /**
     * Register module translations
     */
    protected function registerTranslations()
    {
        \Yii::$app->i18n->translations['training*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@training/messages',
            'fileMap' => [
                'training' => 'training.php',
                'training/courses' => 'courses.php',
                'training/lessons' => 'lessons.php',
                'training/questions' => 'questions.php',
                'training/answers' => 'answers.php',
                'training/tests' => 'tests.php',
            ],
        ];
    }
}