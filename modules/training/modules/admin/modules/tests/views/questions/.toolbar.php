<?php
/**
 * @var \training\modules\admin\models\Test $test
 */
return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('training/tests', 'Back to tests` list'),
                'data-pjax' => 'false'
            ],
            'url' => [
                'tests/index',
                'course_uuid' => $test->course_uuid
            ],
        ],
        [
            'label' => Yii::t('training/tests', 'Select all'),
            'options' => [
                'data-toggle' => 'select',
                'data-state' => 'true',
                'data-pjax' => 'false'
            ],
            'url' => [
                'select',
                'test_uuid' => $test->uuid,
            ],
        ],
        [
            'label' => Yii::t('training/tests', 'Clear selection'),
            'options' => [
                'data-toggle' => 'select',
                'data-state' => 'false',
                'data-pjax' => 'false'
            ],
            'url' => [
                'select',
                'test_uuid' => $test->uuid,
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
];