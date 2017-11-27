<?php

return \yii\helpers\ArrayHelper::merge(
    require_once __DIR__ . "/../modules/fields/config/rules.php",
    [
        'forms/<uuid:[a-z0-9\-]{36}>' => 'forms/index',
        'forms/<action:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'forms/forms/<action>',
    ]
);