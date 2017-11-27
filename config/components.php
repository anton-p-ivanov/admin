<?php

return [
    'assetManager' => [
        'appendTimestamp' => true,
        'linkAssets' => true,
        'bundles' => [
            'yii\web\YiiAsset' => false,
            'yii\grid\GridViewAsset' => false,
            'yii\web\JqueryAsset' => [
                'sourcePath' => '@app/themes/material/js',
                'js' => ['jquery.js']
            ],
            'yii\widgets\PjaxAsset' => [
                'sourcePath' => '@bower/jquery-pjax',
                'js' => ['jquery.pjax.js'],
                'depends' => ['yii\web\JqueryAsset']
            ],
        ]
    ],
    'authManager' => [
        'class' => 'yii\rbac\DbManager',
        'itemTable' => '{{%auth_items}}',
        'itemChildTable' => '{{%auth_items_children}}',
        'assignmentTable' => '{{%auth_assignments}}',
        'ruleTable' => '{{%auth_rules}}',
    ],
    'request' => [
        'cookieValidationKey' => '845W41cLkwvlhwkoDLnjBviBgYa5pA0e',
    ],
    'security' => [
        'passwordHashStrategy' => 'password_hash'
    ],
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
//    'errorHandler' => [
//        'errorAction' => 'site/error',
//    ],
    'formatter' => [
        // Set formatter timezone to UTC if it configured in PHP & MySQL settings.
        // Otherwise set it to your timezone and configure PHP & MySQL timezones to UTC.
        'timeZone' => 'UTC',
        'datetimeFormat' => 'php:d.m.Y H:i',
//        'dateFormat' => 'php:d.m.Y',
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
                'logFile' => '@runtime/logs/' . date('Y/m/d') . '/error.log',
                'enabled' => true,
            ],
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['info'],
                'categories' => ['app\components\filters\Logger::log'],
                'logFile' => '@runtime/logs/' . date('Y/m/d') . '/logger.log',
                'logVars' => [],
                'enabled' => true,
            ],
            [
                'class' => 'yii\log\FileTarget',
                'logVars' => false,
                'enabled' => true,
                'categories' => ['mail'],
                'logFile' => '@runtime/logs/' . date('Y/m/d') . '/mail.log'
            ],
        ],
    ],
    'db' => require __DIR__ . "/db.php",
    'user' => [
        'enableAutoLogin' => true,
        'identityClass' => 'app\models\User',
        'loginUrl' => ['/account/login']
    ],
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => require __DIR__ . "/rules.php"
    ],
];
