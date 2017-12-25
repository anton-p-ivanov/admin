<?php
/**
 * UrlManager rules for main module.
 */

return \yii\helpers\ArrayHelper::merge(
    require __DIR__ . "/../modules/accounts/config/rules.php",
    require __DIR__ . "/../modules/mail/config/rules.php",
    require __DIR__ . "/../modules/forms/config/rules.php",
    require __DIR__ . "/../modules/storage/config/rules.php",
    require __DIR__ . "/../modules/users/config/rules.php",
    [
        'api/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/api/<controller>/<action>',
        'admin/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/admin/<controller>/<action>',
        '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ]
);