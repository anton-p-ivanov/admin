<?php

return [
    'id' => 'admin-v4',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'sourceLanguage' => 'en-US',
    'language' => 'ru-RU',
    'layout' => 'material',
    'components' => require __DIR__ . '/components.php',
    'modules' => require __DIR__ . '/modules.php',
    'params' => require __DIR__ . '/params.php',
    'aliases' => [
        '@bower' => '@vendor/bower-asset'
    ]
//    Uncomment next line to `close` site for maintenance
//    'catchAll' => ['site/offline'],
];
