<?php

namespace forms\modules\fields\controllers;

use forms\models\Form;
use forms\modules\fields\models\Field;
use yii\web\HttpException;

/**
 * Class FieldsController
 * @package forms\modules\fields\controllers
 */
class FieldsController extends \fields\controllers\FieldsController
{
    /**
     * @return string
     * @throws HttpException
     */
    public function actionIndex()
    {
        $form_uuid = \Yii::$app->request->get('form_uuid', -1);
        $form = Form::findOne($form_uuid);

        if (!$form) {
            throw new HttpException(404);
        }

        return $this->renderPartial('@forms/modules/fields/views/fields/index', [
            'form_uuid' => $form_uuid,
            'dataProvider' => Field::search(['form_uuid' => $form_uuid]),
        ]);
    }

    /**
     * @param string $form_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate($form_uuid)
    {
        $model = new Field([
            'active' => true,
            'sort' => 100,
            'type' => Field::FIELD_TYPE_DEFAULT,
            'form_uuid' => $form_uuid,
            'label' => \Yii::t('fields', 'New field'),
            'code' => 'FORM_FIELD_' . \Yii::$app->security->generateRandomString(6)
        ]);

        if (!$model->save()) {
            throw new HttpException(500, 'Could not create new field.');
        }

        \Yii::$app->session->setFlash('FIELD_CREATED');

        return $this->renderPartial('edit', [
            'model' => $model,
            'workflow' => $model->workflow
        ]);
    }
}
