<?php

return yii\helpers\ArrayHelper::merge(
    require (__DIR__ . '/../../../config/test.php'),
    require (__DIR__ . '/../../../config/test/test.php'),
    require (__DIR__ . '/test/test.php')
);
