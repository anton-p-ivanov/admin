<?php

namespace training\modules\admin\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\BaseController;
use training\modules\admin\models\Answer;
use training\modules\admin\models\Question;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

/**
 * Class AnswersController
 *
 * @package training\modules\admin\controllers
 */
class AnswersController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Answer::class;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid && in_array($action->id, ['create'])) {
            if (!($question_uuid = \Yii::$app->request->get('question_uuid'))) {
                throw new BadRequestHttpException();
            }

            if (!Question::findOne(['uuid' => $question_uuid])) {
                throw new HttpException(404, 'Question not found.');
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
                    'question_uuid' => \Yii::$app->request->get('question_uuid'),
                    'sort' => 100,
                ]
            ],
            'edit' => EditAction::class,
            'copy' => CopyAction::class,
            'delete' => DeleteAction::class,
        ];
    }

    /**
     * @param string $question_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($question_uuid)
    {
        $question = Question::findOne($question_uuid);

        if (!$question) {
            throw new HttpException(404, 'Question not found.');
        }

        $params = [
            'dataProvider' => Answer::search($question_uuid),
            'question' => $question,
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }
}
