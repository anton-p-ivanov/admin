<?php
/**
 * UrlManager rules for main module.
 */

return \yii\helpers\ArrayHelper::merge(
    require_once "../modules/forms/config/rules.php",
    require_once "../modules/storage/config/rules.php",
    [
        'api/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/api/<controller>/<action>',
        'admin/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/admin/<controller>/<action>',
        '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ]
);