<?php
/**
 * UrlManager rules for main module.
 */

$rules = [];
$path = realpath(__DIR__ . '/../modules');

foreach(new DirectoryIterator($path) as $item) {
    if ($item->isDir() && !$item->isDot()) {
        $filename = $item->getPathname() . '/config/rules.php';
        if (file_exists($filename)) {
            $rules = \yii\helpers\ArrayHelper::merge($rules, require $filename);
        }
    }
}

return \yii\helpers\ArrayHelper::merge(
    $rules,
    [
        'api/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/api/<controller>/<action>',
        'admin/<module:\w+>' => '<module>/admin',
        'admin/<module:\w+>/<controller:\w+>' => '<module>/admin/<controller>',
        'admin/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/admin/<controller>/<action>',
        '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ]
);