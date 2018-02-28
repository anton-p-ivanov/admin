<?php

return [
    'modules' => require_once __DIR__ . '/modules.php',
    'components' => [
        'i18n' => [
            'class' => yii\i18n\I18N::class,
            'translations' => [
                'accounts*' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@accounts/messages',
                    'fileMap' => [
                        'accounts/addresses' => 'addresses.php',
                        'accounts/contacts' => 'contacts.php',
                        'accounts/fields' => 'fields.php',
                        'accounts/managers' => 'managers.php',
                        'accounts/statuses' => 'statuses.php',
                        'accounts/types' => 'types.php',
                    ]
                ],
                'addresses*' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'addresses' => 'addresses.php',
                    ]
                ],
                'fields*' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@fields/messages',
                    'fileMap' => [
                        'fields' => 'fields.php',
                        'fields/validators' => 'validators.php',
                        'fields/values' => 'values.php'
                    ]
                ],
                'sales*' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@sales/messages',
                    'fileMap' => [
                        'sales' => 'sales.php',
                        'sales/discounts' => 'discounts.php',
                    ]
                ]
            ]
        ]
    ]
];
