<?php

namespace fields\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\actions\IndexAction;
use app\components\BaseController;
use fields\components\traits\Duplicator;
use fields\models\Field;
use fields\models\FieldValidator;
use fields\models\FieldValue;

/**
 * Class FieldsController
 *
 * @package fields\controllers
 */
class FieldsController extends BaseController
{
    use Duplicator;

    /**
     * @var Field
     */
    public $modelClass = Field::class;
    /**
     * @var FieldValue
     */
    public $valueClass = FieldValue::class;
    /**
     * @var FieldValidator
     */
    public $validatorClass = FieldValidator::class;

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
                    'active' => true,
                    'sort' => 100,
                    'type' => Field::FIELD_TYPE_DEFAULT,
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
     * @return array
     */
    public function getIndexParams()
    {
        $params = ['dataProvider' => $this->modelClass::search()];

        $relations = [
            'validators' => $this->validatorClass,
            'values' => $this->valueClass,
        ];

        foreach ($relations as $name => $className) {
            $params[$name] = $className::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('field_uuid')
                ->indexBy('field_uuid')->column();
        }

        return $params;
    }

    /**
     * @param Field $model
     * @param Field $original
     */
    public function afterCopy($model, $original)
    {
        foreach ($original->fieldValues as $value) {
            $this->duplicateValue($value, $model->uuid);
        }

        foreach ($original->fieldValidators as $validator) {
            $this->duplicateValidator($validator, $model->uuid);
        }
    }
}
