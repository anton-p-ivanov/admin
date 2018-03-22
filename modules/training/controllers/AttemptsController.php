<?php

namespace training\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\actions\IndexAction;
use app\components\BaseController;
use training\models\Attempt;
use training\models\AttemptData;
use training\models\Test;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class AttemptsController
 *
 * @package training\controllers
 */
class AttemptsController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Attempt::class;
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

        if ($isValid && in_array($action->id, ['index', 'create'])) {
            if (!($test_uuid = \Yii::$app->request->get('test_uuid'))) {
                throw new BadRequestHttpException();
            }

            if (!($this->_test = Test::findOne(['uuid' => $test_uuid]))) {
                throw new NotFoundHttpException('Test not found.');
            }
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['ajax']['except'] = ['index', 'export'];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'params' => [$this, 'getIndexParams'],
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelConfig' => [
                    'test_uuid' => \Yii::$app->request->get('test_uuid'),
                    'success' => false,
                    'dates' => ['begin_date' => \Yii::$app->formatter->asDatetime(date('Y-m-d H:i:s'))],
                ]
            ],
            'edit' => EditAction::class,
            'copy' => [
                'class' => CopyAction::class,
                'useDeepCopy' => true,
            ],
            'delete' => DeleteAction::class,
        ];
    }

    /**
     * @param Attempt $model
     */
    public function beforeRender($model)
    {
        // Format dates into human readable format
        $model->formatDatesArray();
    }

    /**
     * @return array
     */
    public function getIndexParams()
    {
        return [
            'dataProvider' => Attempt::search(['test_uuid' => $this->_test->uuid]),
            'test' => $this->_test,
        ];
    }

    /**
     * @param Attempt $model
     * @param Attempt $original
     */
    public function afterCopy($model, $original)
    {
        $insert = [];
        $properties = AttemptData::findAll(['attempt_uuid' => $original->uuid]);

        foreach ($properties as $property) {
            $insert[] = [
                'attempt_uuid' => $model->uuid,
                'question_uuid' => $property->question_uuid,
                'answer_uuid' => $property->answer_uuid,
                'value' => $property->value
            ];
        }

        if ($insert) {
            \Yii::$app->db
                ->createCommand()
                ->batchInsert(AttemptData::tableName(), array_keys($insert[0]), $insert)
                ->execute();
        }
    }

    /**
     * @param string $test_uuid
     * @param string $format
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionExport($test_uuid, $format = 'csv')
    {
        // Set response format
        \Yii::$app->response->format = $format;

        /* @var Test $form */
        $test = Test::findOne($test_uuid);
        if (!$test) {
            throw new NotFoundHttpException('Invalid test identifier.');
        }

        $attempts = Attempt::findAll(['test_uuid' => $test_uuid]);

        $data = [];
        foreach ($attempts as $model) {
            $data[$model->uuid] = $this->prepareResultArray($model);
        }

        return $data;
    }

    /**
     * @param Attempt $model
     * @return array
     */
    protected function prepareResultArray(Attempt $model)
    {
        $results = [];
        $data = [
            'user.fullname' => $model->user->getFullName(),
            'user.email' => $model->user->email,
            'user.account' => $model->user->account ? $model->user->account->title : null,
            'success' => \Yii::$app->formatter->asBoolean($model->success),
            'begin_date' => $model->begin_date ? \Yii::$app->formatter->asDatetime($model->begin_date) : null,
            'end_date' => $model->end_date ? \Yii::$app->formatter->asDatetime($model->end_date) : null,
        ];

        foreach ($data as $label => $value) {
            $results[$model->getAttributeLabel($label)] = $value;
        }

        return $results;
    }
}
