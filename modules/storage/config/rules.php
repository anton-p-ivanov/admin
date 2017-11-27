<?php

return [
    'storage/index/<tree_uuid:[a-z0-9\-]{36}>' => 'storage/storage/index',
    'storage/versions/<action:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'storage/versions/<action>',
    'storage/<action:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'storage/storage/<action>',
];