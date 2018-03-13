<?php

namespace training\modules\admin\modules\tests\controllers;

use app\components\BaseController;
use training\modules\admin\models\Question;
use training\modules\admin\models\Test;
use training\modules\admin\models\TestQuestion;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

/**
 * Class QuestionsController
 *
 * @package training\modules\admin\modules\tests\controllers
 */
class QuestionsController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = TestQuestion::class;
    /**
     * @var Test
     */
    private $_test;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid && in_array($action->id, ['index', 'select'])) {
            if (!($test_uuid = \Yii::$app->request->get('test_uuid'))) {
                throw new BadRequestHttpException();
            }

            if (!($this->_test = Test::findOne($test_uuid))) {
                throw new HttpException(404, 'Test not found.');
            }
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['DELETE'],
                'select' => ['POST']
            ]
        ];

        return $behaviors;
    }

    /**
     * @param string $test_uuid
     * @return string
     */
    public function actionIndex($test_uuid)
    {
        $params = [
            'test' => $this->_test,
            'selected' => TestQuestion::find()
                ->where(['test_uuid' => $test_uuid])
                ->select('question_uuid')
                ->column(),
            'lessons' => $this->_test->course
                ->getLessons()
                ->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC])
                ->all()
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param string $test_uuid
     * @return int
     */
    public function actionSelect($test_uuid)
    {
        TestQuestion::deleteAll(['test_uuid' => $test_uuid]);

        if ($post = \Yii::$app->request->post('selection')) {
            $data = [];
            $post = Question::find()
                ->where(['uuid' => $post, 'active' => true])
                ->select('uuid')
                ->column();

            foreach ($post as $item) {
                $data[] = [
                    'test_uuid' => $test_uuid,
                    'question_uuid' => $item
                ];
            }

            return \Yii::$app->db->createCommand()->batchInsert(
                TestQuestion::tableName(),
                ['test_uuid', 'question_uuid'],
                $data
            )->execute();
        }

        return 0;
    }
}
