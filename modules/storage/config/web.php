<?php

return [
    'components' => [
        'i18n' => [
            'class' => yii\i18n\I18N::className(),
            'translations' => [
                'storage*' => [
                    'class' => yii\i18n\PhpMessageSource::className(),
                    'basePath' => '@storage/messages',
                ]
            ]
        ]
    ]
];
