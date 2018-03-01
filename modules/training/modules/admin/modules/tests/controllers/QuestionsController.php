<?php

namespace training\modules\admin\modules\tests\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use training\modules\admin\models\Question;
use training\modules\admin\models\Test;
use training\modules\admin\models\TestQuestion;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class QuestionsController
 *
 * @package training\modules\admin\modules\tests\controllers
 */
class QuestionsController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if (YII_DEBUG && \Yii::$app->user->isGuest) {
            \Yii::$app->user->login(User::findOne(['email' => 'guest.user@example.com']));
        }

        if (\Yii::$app->request->isPost) {
            // Set valid response format
            \Yii::$app->response->format = Response::FORMAT_JSON;
        }

        return $isValid;
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
                'delete' => ['delete'],
            ]
        ];
        $behaviors['confirm'] = [
            'class' => ConfirmFilter::class,
            'actions' => ['delete']
        ];
        $behaviors['ajax'] = [
            'class' => AjaxFilter::class,
            'except' => ['index']
        ];

        return $behaviors;
    }

    /**
     * @param string $test_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($test_uuid)
    {
        $test = Test::findOne($test_uuid);

        if (!$test) {
            throw new HttpException(404, 'Test not found.');
        }

        $params = [
            'test' => $test,
            'selected' => TestQuestion::find()
                ->where(['test_uuid' => $test_uuid])
                ->select('question_uuid')
                ->column(),
            'lessons' => $test->course
                ->getLessons()
                ->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC])
                ->all()
        ];

        return $this->render('index', $params);
    }

    /**
     * @param string $test_uuid
     * @return int
     * @throws HttpException
     */
    public function actionSelect($test_uuid)
    {
        $test = Test::findOne($test_uuid);

        if (!$test) {
            throw new HttpException(404, 'Test not found.');
        }

        if (\Yii::$app->request->isPost) {
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
        }

        return 0;
    }
}
