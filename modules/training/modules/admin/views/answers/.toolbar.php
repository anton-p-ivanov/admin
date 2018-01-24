<?php
/**
 * @var \training\modules\admin\models\Question $question
 */
return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('training/answers', 'Back to questions` list'),
                'data-pjax' => 'false'
            ],
            'url' => [
                'questions/index',
                'lesson_uuid' => $question->lesson_uuid,
            ],
        ],
        [
            'label' => Yii::t('training/answers', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#answers-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ],
            'url' => [
                'create',
                'question_uuid' => $question->uuid,
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
                    'label' => Yii::t('training/answers', 'Refresh'),
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
                'title' => Yii::t('training/answers', 'Delete selected items')
            ],
        ],
    ]
];