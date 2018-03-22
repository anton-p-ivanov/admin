<?php

namespace forms\modules\admin\modules\fields\controllers;

use forms\modules\admin\models\Form;
use forms\modules\admin\modules\fields\models\Field;
use forms\modules\admin\modules\fields\models\FieldValidator;
use forms\modules\admin\modules\fields\models\FieldValue;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

/**
 * Class FieldsController
 *
 * @package forms\modules\admin\modules\fields\controllers
 */
class FieldsController extends \fields\controllers\FieldsController
{
    /**
     * @var string
     */
    public $modelClass = Field::class;
    /**
     * @var string
     */
    public $validatorClass = FieldValidator::class;
    /**
     * @var string
     */
    public $valueClass = FieldValue::class;
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

            if (!($this->_form = Form::findOne(['uuid' => $form_uuid]))) {
                throw new HttpException(404, 'Form not found.');
            }
        }

        if ($action->id === 'index') {
            $this->viewPath = '@forms/modules/admin/modules/fields/views/fields';
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['create']['modelConfig'] = [
            'active' => true,
            'sort' => 100,
            'type' => Field::FIELD_TYPE_DEFAULT,
            'form_uuid' => \Yii::$app->request->get('form_uuid')
        ];

        return $actions;
    }

    /**
     * @return array
     */
    public function getIndexParams()
    {
        $params = parent::getIndexParams();
        $params['form'] = $this->_form;

        return $params;
    }
}
