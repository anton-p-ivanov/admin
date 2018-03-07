<?php

namespace catalogs\modules\admin\modules\fields\controllers;

use app\models\Workflow;
use catalogs\modules\admin\models\Catalog;
use catalogs\modules\admin\modules\fields\models\Group;
use yii\web\HttpException;
use yii\widgets\ActiveForm;

/**
 * Class GroupsController
 *
 * @package catalogs\modules\admin\modules\fields\controllers
 */
class GroupsController extends \fields\controllers\GroupsController
{
    /**
     * @var Group
     */
    public $modelClass = Group::class;

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid) {
            $this->setViewPath('@catalogs/modules/admin/modules/fields/views/groups');
        }

        return $isValid;
    }

    /**
     * @return string
     * @throws HttpException
     */
    public function actionIndex()
    {
        $catalog_uuid = \Yii::$app->request->get('catalog_uuid');
        $catalog = Catalog::find()->where(['uuid' => $catalog_uuid])->multilingual()->one();

        if (!$catalog) {
            throw new HttpException(404, 'Catalog not found.');
        }

        $params = [
            'dataProvider' => Group::search(['catalog_uuid' => $catalog_uuid]),
            'catalog' => $catalog,
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate()
    {
        $catalog_uuid = \Yii::$app->request->get('catalog_uuid');
        $catalog = Catalog::find()->where(['uuid' => $catalog_uuid])->multilingual()->one();

        if (!$catalog) {
            throw new HttpException(404, 'Catalog not found.');
        }

        $model = new Group([
            'catalog_uuid' => $catalog_uuid,
            'active' => true,
            'sort' => 100
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('create', [
            'model' => $model,
            'workflow' => new Workflow()
        ]);
    }

    /**
     * @param string $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        /* @var Group $model */
        $model = Group::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Field`s group not found.');
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('edit', [
            'model' => $model,
            'workflow' => $model->workflow ?: new Workflow()
        ]);
    }

    /**
     * @param string $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCopy($uuid)
    {
        /* @var Group $model */
        $model = Group::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Field`s group not found.');
        }

        // Makes a model`s copy
        $copy = $model->duplicate();

        if ($copy->load(\Yii::$app->request->post())) {
            return $this->postCreate($copy);
        }

        return $this->renderPartial('copy', [
            'model' => $copy,
            'workflow' => new Workflow()
        ]);
    }

    /**
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = Group::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    protected function postCreate($model)
    {
        // Validate user inputs
        $errors = ActiveForm::validate($model);

        if ($errors) {
            \Yii::$app->response->statusCode = 206;
            return $errors;
        }

        $model->save(false);

        return $model->attributes;
    }
}
