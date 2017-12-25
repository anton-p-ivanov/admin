<?php

return \yii\helpers\ArrayHelper::merge(
    require __DIR__ . "/../modules/fields/config/rules.php",
    [
        'accounts/<uuid:[a-z0-9\-]{36}>' => 'accounts/index',
        'accounts/types/<action:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'accounts/types/<action>',
        'accounts/<action:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'accounts/accounts/<action>',
    ]
);