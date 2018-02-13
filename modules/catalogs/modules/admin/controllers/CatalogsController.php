<?php

namespace catalogs\modules\admin\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use app\models\Workflow;
use catalogs\modules\admin\models\Catalog;
use catalogs\modules\admin\models\Type;
use catalogs\modules\admin\modules\fields\components\traits\Duplicator;
use catalogs\modules\admin\modules\fields\models\Field;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class CatalogsController
 *
 * @package catalogs\modules\admin\controllers
 */
class CatalogsController extends Controller
{
    use Duplicator;

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if (YII_DEBUG && \Yii::$app->user->isGuest) {
            \Yii::$app->user->login(User::findOne(['email' => 'guest.user@example.com']));
        }

        if (\Yii::$app->request->isPost) {
            // Set valid response format
            \Yii::$app->response->format = Response::FORMAT_JSON;
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['delete'],
            ]
        ];
        $behaviors['confirm'] = [
            'class' => ConfirmFilter::className(),
            'actions' => ['delete']
        ];
        $behaviors['ajax'] = [
            'class' => AjaxFilter::className(),
            'except' => ['index']
        ];

        return $behaviors;
    }

    /**
     * @param string $type_uuid
     * @return string
     */
    public function actionIndex($type_uuid = null)
    {
        $type = Type::findOne($type_uuid);

        $params = [
            'dataProvider' => Catalog::search(['{{%catalogs}}.[[type_uuid]]' => $type_uuid]),
            'type' => $type,
            'fields' => Field::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('catalog_uuid')
                ->indexBy('catalog_uuid')->column(),
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param string $type_uuid
     * @return array|string
     */
    public function actionCreate($type_uuid = null)
    {
        $model = new Catalog([
            'active' => 1,
            'sort' => 100,
            'type_uuid' => $type_uuid
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
        /* @var Catalog $model */
        $model = $this->getModel($uuid);

        if (!$model) {
            throw new HttpException(404, 'Catalog not found.');
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
     * @param bool $deep
     * @return array|string
     * @throws HttpException
     */
    public function actionCopy($uuid, $deep = false)
    {
        /* @var Catalog $model */
        $model = $this->getModel($uuid);

        if (!$model) {
            throw new HttpException(404, 'Catalog not found.');
        }

        // Makes a model`s copy
        $copy = $model->duplicate();

        if ($copy->load(\Yii::$app->request->post())) {
            return $this->postCreate($copy, $deep ? $model : null);
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
        $models = Catalog::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param Catalog $model
     * @param Catalog $original
     * @return array
     */
    protected function postCreate($model, $original = null)
    {
        // Validate user inputs
        $errors = ActiveForm::validate($model);

        if ($errors) {
            \Yii::$app->response->statusCode = 206;
            return $errors;
        }

        $result = $model->save(false);

        if ($result && $original) {
            foreach ($original->groups as $group) {
                $this->duplicateGroup($group, $model->uuid);
            }

            foreach ($original->fields as $field) {
                $this->duplicateField($field, $model->uuid);
            }
        }

        return $model->attributes;
    }

    /**
     * @param string $uuid
     * @return \yii\db\ActiveRecord|Catalog
     */
    protected function getModel($uuid)
    {
        return Catalog::find()->multilingual()->where(['uuid' => $uuid])->one();
    }
}
