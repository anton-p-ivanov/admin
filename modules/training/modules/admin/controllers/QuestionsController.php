<?php

namespace training\modules\admin\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\BaseController;
use training\modules\admin\models\Answer;
use training\modules\admin\models\Lesson;
use training\modules\admin\models\Question;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

/**
 * Class QuestionsController
 *
 * @package training\modules\admin\controllers
 */
class QuestionsController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Question::class;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid && in_array($action->id, ['create'])) {
            if (!($lesson_uuid = \Yii::$app->request->get('lesson_uuid'))) {
                throw new BadRequestHttpException();
            }

            if (!Lesson::findOne($lesson_uuid)) {
                throw new HttpException(404, 'Lesson not found.');
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
                    'lesson_uuid' => \Yii::$app->request->get('lesson_uuid'),
                    'active' => true,
                    'sort' => 100,
                    'type' => Question::TYPE_DEFAULT
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
     * @param string $lesson_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($lesson_uuid)
    {
        $lesson = Lesson::findOne($lesson_uuid);

        if (!$lesson) {
            throw new HttpException(404, 'Lesson not found.');
        }

        $params = [
            'dataProvider' => Question::search($lesson_uuid),
            'lesson' => $lesson,
            'answers' => Answer::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('question_uuid')
                ->indexBy('question_uuid')->column(),
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param Question $model
     * @param Question $original
     */
    public function afterCopy($model, $original)
    {
        foreach ($original->answers as $answer) {
            $answer->question_uuid = $model->uuid;
            $answer->duplicate()->save();
        }
    }
}
