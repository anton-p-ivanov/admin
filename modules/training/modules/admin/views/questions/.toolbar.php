<?php
/**
 * @var \training\modules\admin\models\Lesson $lesson
 */
return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('training/questions', 'Back to lessons` list'),
                'data-pjax' => 'false'
            ],
            'url' => [
                'lessons/index',
                'course_uuid' => $lesson->course_uuid,
            ],
        ],
        [
            'label' => Yii::t('training/questions', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#questions-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ],
            'url' => [
                'create',
                'lesson_uuid' => $lesson->uuid,
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
                    'label' => Yii::t('training/questions', 'Refresh'),
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
                'title' => Yii::t('training/questions', 'Delete selected items')
            ],
        ],
    ]
];