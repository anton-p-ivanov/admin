<?php

namespace training\modules\admin\modules\tests\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\BaseController;
use training\modules\admin\models\Attempt;
use training\modules\admin\models\Course;
use training\modules\admin\models\Test;
use training\modules\admin\models\TestQuestion;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

/**
 * Class TestsController
 *
 * @package training\modules\admin\modules\tests\controllers
 */
class TestsController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Test::class;
    /**
     * @var Course
     */
    private $_course;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid && in_array($action->id, ['index', 'create'])) {
            if (!($course_uuid = \Yii::$app->request->get('course_uuid'))) {
                throw new BadRequestHttpException();
            }

            if (!($this->_course = Course::findOne($course_uuid))) {
                throw new HttpException(404, 'Training course not found.');
            }
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'create' => [
                'class' => CreateAction::class,
                'modelConfig' => [
                    'course_uuid' => \Yii::$app->request->get('course_uuid'),
                    'active' => true,
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
        $params = [
            'dataProvider' => Test::search(),
            'course' => $this->_course,
        ];

        $relations = [
            'questions' => TestQuestion::class,
            'attempts' => Attempt::class
        ];

        foreach ($relations as $name => $className) {
            $params[$name] = $className::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('test_uuid')
                ->indexBy('test_uuid')->column();
        }

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param Test $model
     * @param Test $original
     */
    public function afterCopy($model, $original)
    {
        $this->duplicateQuestions($model, $original);
    }

    /**
     * @param Test $original
     * @param Test $model
     */
    protected function duplicateQuestions($model, $original)
    {
        $data = [];
        $questions = TestQuestion::find()
            ->where(['test_uuid' => $original->uuid])
            ->select('question_uuid')
            ->column();

        foreach ($questions as $question) {
            $data[] = [
                'test_uuid' => $model->uuid,
                'question_uuid' => $question
            ];
        }

        \Yii::$app->db->createCommand()
            ->batchInsert(TestQuestion::tableName(), ['test_uuid', 'question_uuid'], $data)
            ->execute();
    }
}
