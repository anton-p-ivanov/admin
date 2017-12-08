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
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate()
    {
        $form = Form::findOne(\Yii::$app->request->get('form_uuid'));

        if (!$form) {
            throw new HttpException(404, 'Form not found.');
        }

        $model = new Field([
            'active' => true,
            'sort' => 100,
            'type' => Field::FIELD_TYPE_DEFAULT,
            'form_uuid' => $form->uuid,
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

    /**
     * @param string $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        /* @var Field $model */
        $model = Field::findOne($uuid);

        if (!$model) {
            throw new HttpException(404);
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('edit', [
            'model' => $model,
            'workflow' => $model->workflow
        ]);
    }

    /**
     * @param string $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCopy($uuid)
    {
        /* @var Field $model */
        $model = Field::findOne($uuid);

        if (!$model) {
            throw new HttpException(404);
        }

        // Makes a form`s copy
        $copy = $model->duplicate();

        return $this->renderPartial('copy', [
            'model' => $copy,
            'workflow' => $copy->workflow
        ]);
    }

    /**
     * We need to override this method to trigger \forms\modules\fields\models\Field::afterDelete() method.
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = Field::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }
}
