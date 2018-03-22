<?php

namespace training\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\actions\IndexAction;
use app\components\BaseController;
use training\models\Attempt;
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
        $dataProvider = Attempt::search(['test_uuid' => $this->_test->uuid]);
//        $fields = array_filter($this->_test->fields, function ($field) {
//            return $field->list === 1;
//        });
//
//        $properties = ResultProperty::findAll([
//            'field_uuid' => ArrayHelper::getColumn($fields, 'uuid'),
//            'result_uuid' => ArrayHelper::getColumn($dataProvider->models, 'uuid')
//        ]);
//
//        $sorted = [];
//
//        foreach ($properties as $property) {
//            $sorted[$property->result_uuid][$property->field_uuid] = $property->value;
//        }

        return [
            'dataProvider' => $dataProvider,
            'test' => $this->_test,
//            'fields' => $fields,
//            'properties' => $sorted
        ];
    }
//
//    /**
//     * @param Result $model
//     * @param Result $original
//     */
//    public function afterCopy($model, $original)
//    {
//        $insert = [];
//        $properties = ResultProperty::findAll(['result_uuid' => $original->uuid]);
//
//        foreach ($properties as $property) {
//            $insert[] = [
//                'result_uuid' => $model->uuid,
//                'field_uuid' => $property->field_uuid,
//                'value' => $property->value
//            ];
//        }
//
//        if ($insert) {
//            \Yii::$app->db
//                ->createCommand()
//                ->batchInsert(ResultProperty::tableName(), array_keys($insert[0]), $insert)
//                ->execute();
//        }
//    }
//
//    /**
//     * @param string $form_uuid
//     * @param string $format
//     * @return array
//     * @throws NotFoundHttpException
//     */
//    public function actionExport($form_uuid, $format = 'csv')
//    {
//        // Set response format
//        \Yii::$app->response->format = $format;
//
//        /* @var Form $form */
//        $form = Form::findOne($form_uuid);
//        if (!$form) {
//            throw new NotFoundHttpException('Form not found.');
//        }
//
//        $results = Result::findAll(['form_uuid' => $form_uuid]);
//
//        $data = [];
//        foreach ($results as $model) {
//            $data[$model->uuid] = $this->prepareResultArray($model);
//        }
//
//        return $data;
//    }
//
//    /**
//     * @param Result $model
//     * @return array
//     */
//    protected function prepareResultArray(Result $model)
//    {
//        $workflow = $model->workflow ?: new Workflow();
//
//        $results = [];
//        $data = [
//            'status.title' => $model->status->title,
//            'workflow.created.fullname' => $workflow->created ? $workflow->created->getFullName() : null,
//            'workflow.modified_date' => \Yii::$app->formatter->asDatetime($workflow->modified_date),
//        ];
//
//        foreach ($data as $label => $value) {
//            $results[$model->getAttributeLabel($label)] = $value;
//        }
//
//        return ArrayHelper::merge($this->getResultData($model), $results);
//    }
//
//    /**
//     * @param Result $model
//     * @return array
//     */
//    protected function getResultData(Result $model)
//    {
//        $fields = ArrayHelper::map($model->form->fields, 'uuid', 'label');
//        $properties = ResultProperty::find()
//            ->where(['result_uuid' => $model->uuid])
//            ->indexBy('field_uuid')
//            ->select('value')
//            ->column();
//
//        $sorted = [];
//
//        foreach ($fields as $uuid => $label) {
//            $sorted[$label] = $properties[$uuid];
//        }
//
//        return $sorted;
//    }
}
