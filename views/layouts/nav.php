<?php

$types = [];
$index = 0;

Yii::setAlias('@catalogs', '@app/modules/catalogs');

foreach (\catalogs\models\Type::getList() as $uuid => $type) {
    $types[] = [
        'label' => $type,
        'url' => ['/catalogs/catalogs/', 'type_uuid' => $uuid],
    ];
}

if ($types) {
    $types[] = ['options' => ['class' => 'divider']];
}

$types[] = [
    'label' => 'Catalogs: Manage',
    'url' => ['/admin/catalogs/types'],
];

return [
    [
        'label' => 'Catalogs',
        'url' => ['/catalogs'],
        'active' => strpos(Yii::$app->controller->module->uniqueId, 'catalogs') === 0,
        'items' => $types
    ],
    [
        'label' => 'Storage',
        'url' => ['/storage'],
    ],
    [
        'label' => 'Control panel',
        'url' => ['/admin'],
        'active' => strpos(Yii::$app->controller->module->uniqueId, 'admin') === 0,
        'items' => [
            [
                'label' => 'Users',
                'url' => ['/users/users/index'],
            ],
            [
                'label' => 'Users: Fields',
                'url' => ['/users/admin/fields'],
            ],
            [
                'label' => 'Users: Roles',
                'url' => ['/users/admin/roles'],
            ],
            [
                'options' => ['class' => 'divider']
            ],
            [
                'label' => 'Accounts',
                'url' => ['/accounts/accounts/index'],
            ],
            [
                'label' => 'Accounts: Types',
                'url' => ['/accounts/admin/types'],
            ],
            [
                'label' => 'Accounts: Fields',
                'url' => ['/accounts/admin/fields'],
            ],
            [
                'options' => ['class' => 'divider']
            ],
            [
                'label' => 'Forms',
                'url' => ['/forms/forms/index'],
            ],
            [
                'label' => 'Forms: Manage',
                'url' => ['/forms/admin/forms'],
            ],
            [
                'options' => ['class' => 'divider']
            ],
            [
                'label' => 'Mail: Templates',
                'url' => ['/admin/mail/templates'],
            ],
            [
                'label' => 'Mail: Types',
                'url' => ['/admin/mail/types'],
            ],
            [
                'options' => ['class' => 'divider']
            ],
            [
                'label' => 'Training',
                'url' => ['/training'],
                'options' => ['class' => 'disabled']
            ],
            [
                'label' => 'Training: Courses',
                'url' => ['/admin/training/courses'],
            ],
            [
                'label' => 'Training: Certificates',
                'url' => ['/admin/training/certificates'],
                'options' => ['class' => 'disabled']
            ],
            [
                'options' => ['class' => 'divider']
            ],
            [
                'label' => 'Partnership: Statuses',
                'url' => ['/partnership/statuses'],
            ],
            [
                'label' => 'Sales: Discounts',
                'url' => ['/sales/discounts/types'],
            ],
            [
                'options' => ['class' => 'divider']
            ],
            [
                'label' => 'Sites',
                'url' => ['/admin/sites'],
            ],
            [
                'label' => 'Languages',
                'url' => ['/admin/i18n/languages'],
            ],
        ]
    ],
];