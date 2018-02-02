<?php

return yii\helpers\ArrayHelper::merge(
    require (__DIR__ . '/../../../config/web.php'),
    require (__DIR__ . '/../../../config/test/web.php'),
    require (__DIR__ . '/test/test.php')
);
