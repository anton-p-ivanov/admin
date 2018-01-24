<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Test $test
 */
?>

<p>Training course "<?= $test->course->title; ?>" does not have any active and published lesson.
    To choose test`s questions, add at least one lesson with questions.</p>

<p><?= \yii\helpers\Html::a('View lessons', ['/training/admin/lessons/index', 'course_uuid' => $test->course_uuid], [
        'class' => 'btn btn_primary',
        'data-pjax' => 'false',
    ]); ?></p>
