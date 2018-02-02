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
                        'accounts/types' => 'types.php',
                        'accounts/fields' => 'fields.php'
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
                ]
            ]
        ]
    ]
];
