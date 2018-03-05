<?php

namespace forms\modules\admin\modules\fields;

use forms\modules\admin\modules\fields\models\Field;
use forms\modules\admin\modules\fields\models\FieldValidator;
use forms\modules\admin\modules\fields\models\FieldValue;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package forms\modules\admin\modules\fields
 */
class Module extends \yii\base\Module
{
    /**
     * @var array
     */
    public $controllerMap = [
//        'fields' => [
//            'class' => 'fields\controllers\FieldsController',
//            'modelClass' => Field::class,
//            'valueClass' => FieldValue::class,
//            'validatorClass' => FieldValidator::class,
//            'viewPath' => '@forms/modules/admin/modules/fields/views/fields'
//        ],
        'values' => [
            'class' => 'fields\controllers\ValuesController',
            'modelClass' => FieldValue::class,
            'fieldClass' => Field::class,
            'viewPath' => '@forms/modules/admin/modules/fields/views/values'
        ],
        'validators' => [
            'class' => 'fields\controllers\ValidatorsController',
            'modelClass' => FieldValidator::class,
            'fieldClass' => Field::class,
            'viewPath' => '@forms/modules/admin/modules/fields/views/validators',
        ],
    ];

    /**
     * @var string
     */
    public $defaultRoute = 'fields';
    /**
     * @var string
     */
    public static $title = 'Fields';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $config = require_once(__DIR__ . '/config/web.php');
        $env = require_once(__DIR__ . '/config/' . YII_ENV .'/web.php');

        // initialize the module with the configuration loaded from config.php
        \Yii::configure($this, ArrayHelper::merge($config, $env));

        // set alias
        \Yii::setAlias('@fields', '@app/modules/fields');

        // set view path
        $this->viewPath = '@fields/views';
    }
}