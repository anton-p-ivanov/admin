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
                'title' => Yii::t('training/tests', 'Back to courses` list'),
                'data-pjax' => 'false'
            ],
            'url' => [
                '/training/admin/courses/index',
            ],
        ],
        [
            'label' => Yii::t('training/tests', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#tests-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ],
            'url' => [
                'create',
                'course_uuid' => $course->uuid
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
                    'label' => Yii::t('training/tests', 'Refresh'),
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
                'title' => Yii::t('training/tests', 'Delete selected items')
            ],
        ],
    ]
];