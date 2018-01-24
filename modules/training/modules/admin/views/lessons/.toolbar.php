<?php
/**
 * @var \training\modules\admin\models\Course $course
 */
return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('training/lessons', 'Back to courses` list'),
                'data-pjax' => 'false'
            ],
            'url' => [
                'courses/index',
            ],
        ],
        [
            'label' => Yii::t('training/lessons', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#lessons-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ],
            'url' => [
                'create',
                'course_uuid' => $course->uuid,
            ],
        ]
    ],
    'group-2' => [
        [
            'label' => '<i class="material-icons">more_vert</i>',
            'encode' => false,
            'menuOptions' => ['class' => 'dropdown dropdown_right'],
            'items' => [
                [
                    'label' => Yii::t('training/lessons', 'Refresh'),
                    'url' => \yii\helpers\Url::current()
                ]
            ]
        ],
    ],
    'selected' => [
         [
            'label' => '<i class="material-icons">delete</i>',
            'encode' => false,
            'url' => ['delete'],
            'options' => [
                'data-http-method' => 'delete',
                'data-confirm' => 'true',
                'data-toggle' => 'action',
                'data-pjax' => 'false',
                'title' => Yii::t('training/lessons', 'Delete selected items')
            ],
        ],
    ]
];