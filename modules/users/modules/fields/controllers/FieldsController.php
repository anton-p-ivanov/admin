<?php

namespace users\modules\fields\controllers;

use users\modules\fields\models\Field;
use users\modules\fields\models\FieldFilter;
use users\modules\fields\models\FieldSettings;
use yii\filters\AjaxFilter;
use yii\web\HttpException;

/**
 * Class FieldsController
 * @package users\modules\fields\controllers
 */
class FieldsController extends \fields\controllers\FieldsController
{
    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'filter' => [
                'class' => '\app\components\actions\FilterAction',
                'modelClass' => FieldFilter::className(),
                'viewFile' => '@users/modules/fields/views/fields/filter'
            ],
            'settings' => [
                'class' => '\app\components\actions\SettingsAction',
                'modelClass' => FieldSettings::className(),
                'viewFile' => '@users/modules/fields/views/fields/settings'
            ],
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['ajax'] = [
            'class' => AjaxFilter::className(),
            'except' => ['index']
        ];

        return $behaviors;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $filter = null;
        $settings = FieldSettings::loadSettings();
        $dataProvider = Field::search($settings);

        if ($filter_uuid = \Yii::$app->request->get('filter_uuid')) {
            $filter = FieldFilter::loadFilter($filter_uuid);
            $filter->buildQuery($dataProvider->query);
        }

        $params = [
            'dataProvider' => $dataProvider,
            'settings' => $settings,
            'isFiltered' => $filter ? $filter->isActive : false,
        ];

        $viewFile = '@users/modules/fields/views/fields/index';

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
        $model = new Field([
            'active' => true,
            'sort' => 100,
            'type' => Field::FIELD_TYPE_DEFAULT,
            'label' => \Yii::t('fields', 'New field'),
            'code' => 'USER_FIELD_' . \Yii::$app->security->generateRandomString(6)
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
     * @param bool $deepCopy
     * @return array|string
     * @throws HttpException
     */
    public function actionCopy($uuid, $deepCopy = false)
    {
        /* @var Field $model */
        $model = Field::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Field not found.');
        }

        // Makes a form`s copy
        $copy = $model->duplicate($deepCopy);

        return $this->renderPartial('copy', [
            'model' => $copy,
            'workflow' => $copy->workflow
        ]);
    }

    /**
     * We need to override this method to trigger \users\modules\fields\models\Field::afterDelete() method.
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
