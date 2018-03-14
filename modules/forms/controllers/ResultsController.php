<?php

namespace forms\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\actions\IndexAction;
use app\components\BaseController;
use forms\models\Form;
use forms\models\Result;
use forms\models\ResultProperty;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class ResultsController
 *
 * @package forms\controllers
 */
class ResultsController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Result::class;
    /**
     * @var Form
     */
    private $_form;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid && in_array($action->id, ['index', 'create'])) {
            if (!($form_uuid = \Yii::$app->request->get('form_uuid'))) {
                throw new BadRequestHttpException();
            }

            if (!($this->_form = Form::findOne($form_uuid))) {
                throw new NotFoundHttpException('Form not found.');
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
            'index' => [
                'class' => IndexAction::class,
                'params' => [$this, 'getIndexParams'],
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelConfig' => [
                    'form_uuid' => \Yii::$app->request->get('form_uuid'),
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
     * @return array
     */
    public function getIndexParams()
    {
        $dataProvider = Result::search(['{{%forms_results}}.[[form_uuid]]' => $this->_form->uuid]);
        $fields = array_filter($this->_form->fields, function ($field) {
            return $field->list === 1;
        });

        $properties = ResultProperty::findAll([
            'field_uuid' => ArrayHelper::getColumn($fields, 'uuid'),
            'result_uuid' => ArrayHelper::getColumn($dataProvider->models, 'uuid')
        ]);

        $sorted = [];

        foreach ($properties as $property) {
            $sorted[$property->result_uuid][$property->field_uuid] = $property->value;
        }

        return [
            'dataProvider' => $dataProvider,
            'form' => $this->_form,
            'fields' => $fields,
            'properties' => $sorted
        ];
    }

    /**
     * @param Result $model
     * @param Result $original
     */
    public function afterCopy($model, $original)
    {
        $insert = [];
        $properties = ResultProperty::findAll(['result_uuid' => $original->uuid]);

        foreach ($properties as $property) {
            $insert[] = [
                'result_uuid' => $model->uuid,
                'field_uuid' => $property->field_uuid,
                'value' => $property->value
            ];
        }

        if ($insert) {
            \Yii::$app->db
                ->createCommand()
                ->batchInsert(ResultProperty::tableName(), array_keys($insert[0]), $insert)
                ->execute();
        }
    }
}
