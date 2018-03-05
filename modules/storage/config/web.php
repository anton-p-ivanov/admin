<?php

return [
    'components' => [
        'i18n' => [
            'class' => yii\i18n\I18N::class,
            'translations' => [
                'storage*' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@storage/messages',
                ]
            ]
        ]
    ]
];
