<?php

$types = [];
$index = 0;

Yii::setAlias('@catalogs', '@app/modules/catalogs');

foreach (\catalogs\models\Type::getList() as $uuid => $type) {
    $types[] = [
        'label' => (++$index) . '. ' . $type,
        'url' => ['/catalogs/catalogs/', 'type_uuid' => $uuid],
    ];
}

if ($types) {
    $types[] = '<li class="divider"></li>';
}

$types[] = [
    'label' => 'Manage',
    'url' => ['/admin/catalogs'],
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
                'url' => ['/admin/users'],
            ],
            [
                'label' => 'Roles',
                'url' => ['/admin/roles'],
                'options' => ['class' => 'disabled']
            ],
            [
                'options' => ['class' => 'divider']
            ],
            [
                'label' => 'Accounts',
                'url' => ['/admin/accounts'],
            ],
            [
                'label' => 'Accounts: Types',
                'url' => ['/admin/accounts'],
            ],
            [
                'options' => ['class' => 'divider']
            ],
            [
                'label' => 'Web-forms',
                'url' => ['/admin/forms'],
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
                'label' => 'Training: Courses',
                'url' => ['/training/courses'],
                'options' => ['class' => 'disabled']
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
                'label' => 'Sites',
                'url' => ['/admin/sites'],
                'options' => ['class' => 'disabled']
            ],
            [
                'label' => 'Languages',
                'url' => ['/admin/i18n/languages'],
            ],
        ]
    ],
];