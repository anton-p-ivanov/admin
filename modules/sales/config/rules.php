<?php

return [
    'sales/<controller:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'sales/<controller>/index',
    'sales/<controller:[\w]+>/<action:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'sales/<controller>/<action>',
];