<?php

return \yii\helpers\ArrayHelper::merge(
    require __DIR__ . "/../modules/admin/config/rules.php",
    [
        'catalogs/<controller:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'catalogs/<controller>/index',
        'catalogs/<controller:[\w]+>/<action:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'catalogs/<controller>/<action>',
    ]
);