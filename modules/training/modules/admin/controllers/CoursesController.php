<?php

namespace training\modules\admin\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\BaseController;
use training\modules\admin\components\traits\Duplicator;
use training\modules\admin\models\Course;
use training\modules\admin\models\Lesson;
use training\modules\admin\models\Test;

/**
 * Class CoursesController
 *
 * @package training\modules\admin\controllers
 */
class CoursesController extends BaseController
{
    use Duplicator;

    /**
     * @var string
     */
    public $modelClass = Course::class;

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'create' => [
                'class' => CreateAction::class,
                'modelConfig' => [
                    'active' => true,
                    'sort' => 100
                ]
            ],
            'edit' => EditAction::class,
            'copy' => [
                'class' => CopyAction::class,
                'useDeepCopy' => (int) \Yii::$app->request->get('deep') === 1
            ],
            'delete' => DeleteAction::class,
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $params = ['dataProvider' => Course::search()];

        $relations = [
            'lessons' => Lesson::class,
            'tests' => Test::class
        ];

        foreach ($relations as $name => $className) {
            $params[$name] = $className::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('course_uuid')
                ->indexBy('course_uuid')->column();
        }

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param $model
     * @param $original
     */
    public function afterCopy($model, $original)
    {
        foreach ($original->lessons as $lesson) {
            $this->duplicateLesson($lesson, $model->uuid);
        }

        foreach ($original->tests as $test) {
            $this->duplicateTest($test, $model->uuid);
        }
    }
}
