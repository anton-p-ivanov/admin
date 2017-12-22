<?php

return [
    'partnership/<controller:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'partnership/<controller>/index',
    'partnership/<controller:[\w]+>/<action:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'partnership/<controller>/<action>',
];