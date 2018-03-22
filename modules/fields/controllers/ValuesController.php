<?php

namespace fields\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\actions\IndexAction;
use app\components\BaseController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class ValuesController
 * @package fields\controllers
 */
class ValuesController extends BaseController
{
    /**
     * @var string|\fields\models\FieldValidator
     */
    public $modelClass;
    /**
     * @var  string|\fields\models\Field
     */
    public $fieldClass;
    /**
     * @var \fields\models\Field
     */
    private $_field;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid && in_array($action->id, ['index', 'create'])) {
            if (!($field_uuid = \Yii::$app->request->get('field_uuid'))) {
                throw new BadRequestHttpException();
            }

            if (!($this->_field = $this->fieldClass::findOne(['uuid' => $field_uuid]))) {
                throw new NotFoundHttpException('Field not found.');
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
                'params' => [$this, 'getIndexParams']
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelConfig' => [
                    'field_uuid' => \Yii::$app->request->get('field_uuid'),
                    'sort' => 100,
                ]
            ],
            'edit' => EditAction::class,
            'copy' => CopyAction::class,
            'delete' => DeleteAction::class,
        ];
    }

    /**
     * @return array
     */
    public function getIndexParams()
    {
        return [
            'dataProvider' => $this->modelClass::search([
                'field_uuid' => $this->_field->uuid
            ]),
            'field' => $this->_field,
        ];
    }
}
