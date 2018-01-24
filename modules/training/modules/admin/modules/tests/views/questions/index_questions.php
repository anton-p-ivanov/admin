<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Test $test
 * @var \training\modules\admin\models\Lesson $lesson
 * @var array $selected
 */
?>
<?= \app\widgets\grid\GridView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $lesson->getQuestions()->andWhere(['active' => true]),
        'sort' => false,
        'pagination' => false
    ]),
    'emptyText' => 'No questions found in this lesson.',
    'tableOptions' => ['class' => implode(' ', [
        'grid-view__table',
        'grid-view__table_fixed'
    ])],
    'columns' => require ".grid.php"
]); ?>