<?php

return [
    'admin/catalogs/<controller:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'catalogs/admin/<controller>/index',
    'admin/catalogs/<controller:[\w]+>/<action:[\w]+>/<uuid:[a-z0-9\-]{36}>' => 'catalogs/admin/<controller>/<action>',
];