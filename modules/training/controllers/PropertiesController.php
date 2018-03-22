<?php

namespace training\controllers;

use training\models\Answer;
use training\models\Attempt;
use training\models\AttemptData;
use training\models\Question;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;

/**
 * Class PropertiesController
 *
 * @package training\controllers
 */
class PropertiesController extends \fields\controllers\PropertiesController
{
    /**
     * @param string $attempt_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($attempt_uuid)
    {
        $attempt = Attempt::findOne($attempt_uuid);

        if (!$attempt) {
            throw new NotFoundHttpException('Invalid attempt identifier.');
        }

        $properties = AttemptData::find()
            ->where(['attempt_uuid' => $attempt_uuid])
            ->joinWith('question')
            ->all();

        $properties = ArrayHelper::map($properties, 'answer.uuid', 'answer', 'question_uuid');

        $params = [
            'dataProvider' => AttemptData::search(['test_uuid' => $attempt->test_uuid]),
            'attempt' => $attempt,
            'properties' => $properties,
            'questions' => $this->getValidState($properties)
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param $properties
     * @return array
     */
    protected function getValidState($properties)
    {
        $questions = [];

        foreach ($properties as $question_uuid => $answers) {
            $isValid = null;

            array_walk($answers, function (Answer $answer) use (&$isValid) {
                $isValid = is_bool($isValid) ? $isValid && $answer->isValid() : $answer->isValid();
            });

            $questions[$question_uuid] = $isValid;
        }

        return $questions;
    }

    /**
     * @param string $attempt_uuid
     * @param string $question_uuid
     * @return array|string
     */
    public function actionEdit($attempt_uuid, $question_uuid)
    {
        /**
         * @var Attempt $attempt
         * @var Question $question
         */
        extract($this->getModels($attempt_uuid, $question_uuid));

        $params = [
            'attempt_uuid' => $attempt_uuid,
            'question_uuid' => $question_uuid,
        ];

        /* @var AttemptData $model */
        $model = AttemptData::findOne($params) ?: new AttemptData($params);
        $model->answer_uuid = AttemptData::find()->where($params)->select('answer_uuid')->column();

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postUpdate($model);
        }

        return $this->renderPartial('edit', [
            'model' => $model,
            'question' => $question
        ]);
    }

    /**
     * @param string $attempt_uuid
     * @param string $question_uuid
     * @return int
     */
    public function actionDelete($attempt_uuid, $question_uuid)
    {
        $this->getModels($attempt_uuid, $question_uuid);

        $params = [
            'attempt_uuid' => $attempt_uuid,
            'question_uuid' => $question_uuid,
        ];

        return AttemptData::deleteAll($params);
    }

    /**
     * @param string $attempt_uuid
     * @param string $question_uuid
     * @return array
     * @throws NotFoundHttpException
     */
    protected function getModels($attempt_uuid, $question_uuid)
    {
        $attempt = Attempt::findOne($attempt_uuid);

        if (!$attempt) {
            throw new NotFoundHttpException('Invalid attempt identifier.');
        }

        $question = Question::find()->joinWith('test')->where([
            '{{%training_questions}}.[[uuid]]' => $question_uuid,
            '{{%training_tests_questions}}.[[test_uuid]]' => $attempt->test_uuid])->one();

        if (!($question instanceof Question)) {
            throw new NotFoundHttpException('Invalid question identifier.');
        }

        return [
            'attempt' => $attempt,
            'question' => $question
        ];
    }

    /**
     * @param AttemptData $model
     * @return array
     */
    protected function postUpdate($model)
    {
        // Validate user inputs
        $errors = ActiveForm::validate($model);

        if ($errors) {
            \Yii::$app->response->statusCode = 206;
            return $errors;
        }

        AttemptData::deleteAll([
            'attempt_uuid' => $model->attempt_uuid,
            'question_uuid' => $model->question_uuid
        ]);

        $answers = is_array($model->answer_uuid) ? $model->answer_uuid : [$model->answer_uuid];
        foreach ($answers as $answer_uuid) {
            if ($answer_uuid) {
                (new AttemptData([
                    'attempt_uuid' => $model->attempt_uuid,
                    'question_uuid' => $model->question_uuid,
                    'answer_uuid' => $answer_uuid
                ]))->insert(false);
            }
        }

        return $model->attributes;
    }
}
