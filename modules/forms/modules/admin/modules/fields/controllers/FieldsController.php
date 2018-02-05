<?php

namespace forms\modules\admin\modules\fields\controllers;

use app\models\Workflow;
use forms\models\Form;
use forms\modules\admin\modules\fields\models\Field;
use forms\modules\admin\modules\fields\models\FieldValidator;
use forms\modules\admin\modules\fields\models\FieldValue;
use yii\web\HttpException;

/**
 * Class FieldsController
 *
 * @package forms\modules\admin\modules\fields\controllers
 */
class FieldsController extends \fields\controllers\FieldsController
{
    /**
     * @var string|\yii\db\ActiveRecord
     */
    public $modelClass = Field::class;

    /**
     * @return string
     * @throws HttpException
     */
    public function actionIndex()
    {
        $form_uuid = \Yii::$app->request->get('form_uuid', -1);
        $form = Form::findOne($form_uuid);

        if (!$form) {
            throw new HttpException(404, 'Form not found.');
        }

        $dataProvider = Field::search(['form_uuid' => $form_uuid]);

        $params = [
            'dataProvider' => $dataProvider,
            'form' => $form,
            'validators' => FieldValidator::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('field_uuid')
                ->indexBy('field_uuid')->column(),
            'values' => FieldValue::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('field_uuid')
                ->indexBy('field_uuid')->column(),
        ];

        $viewFile = '@forms/modules/admin/modules/fields/views/fields/index';

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial($viewFile, $params);
        }

        return $this->render($viewFile, $params);
    }

    /**
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate()
    {
        $form_uuid = \Yii::$app->request->get('form_uuid', -1);
        $form = Form::findOne($form_uuid);

        if (!$form) {
            throw new HttpException(404, 'Form not found.');
        }

        /* @var Field $model */
        $model = new $this->modelClass([
            'form_uuid' => $form_uuid,
            'active' => true,
            'sort' => 100,
            'type' => Field::FIELD_TYPE_DEFAULT,
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('create', [
            'model' => $model,
            'workflow' => new Workflow()
        ]);
    }
}
