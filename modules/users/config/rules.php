<?php

return \yii\helpers\ArrayHelper::merge(
    require __DIR__ . "/../modules/fields/config/rules.php",
    [
        'users/<uuid:[a-z0-9\-]{36}>' => 'users/index',
        'users/<action:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'users/users/<action>',
    ]
);