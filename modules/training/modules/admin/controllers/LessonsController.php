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
use training\modules\admin\models\Question;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

/**
 * Class LessonsController
 *
 * @package training\modules\admin\controllers
 */
class LessonsController extends BaseController
{
    use Duplicator;
    /**
     * @var string
     */
    public $modelClass = Lesson::class;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid && in_array($action->id, ['create'])) {
            if (!($course_uuid = \Yii::$app->request->get('course_uuid'))) {
                throw new BadRequestHttpException();
            }

            if (!Course::findOne(['uuid' => $course_uuid])) {
                throw new HttpException(404, 'Training course not found.');
            }
        }

        return $isValid;
    }

    /**
     * @param string $course_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($course_uuid)
    {
        $course = Course::findOne($course_uuid);

        if (!$course) {
            throw new HttpException(404, 'Training course not found.');
        }

        $params = [
            'dataProvider' => Lesson::search($course_uuid),
            'course' => $course,
            'questions' => Question::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('lesson_uuid')
                ->indexBy('lesson_uuid')->column(),
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
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
     * @param Lesson $model
     * @param Lesson $original
     */
    public function afterCopy($model, $original)
    {
        foreach ($original->questions as $question) {
            $this->duplicateQuestion($question, $model->uuid);
        }
    }
}