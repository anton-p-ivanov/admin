<?php

namespace storage\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use storage\models\Storage;
use storage\models\StorageFilter;
use storage\models\StorageSettings;
use storage\models\StorageTree;
use yii\filters\AjaxFilter;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class StorageController
 * @package storage\controllers
 */
class StorageController extends Controller
{
    /**
     * Action constants
     */
    const ACTION_RESET = 'reset';

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

        return $isValid;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'download' => [
                'class' => 'storage\actions\DownloadAction',
                'modelClass' => 'storage\models\StorageFile'
            ],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['delete'],
                'upload' => ['put']
            ]
        ];
        $behaviors['cn'] = [
            'class' => ContentNegotiator::class,
            'only' => ['upload'],
            'formats' => ['application/json' => Response::FORMAT_JSON]
        ];
        $behaviors['confirm'] = [
            'class' => ConfirmFilter::class,
            'actions' => ['delete']
        ];
        $behaviors['ajax'] = [
            'class' => AjaxFilter::class,
            'except' => ['index', 'download']
        ];

        return $behaviors;
    }

    /**
     * @param string|null $tree_uuid
     * @param string|null $filter_uuid
     * @return string
     */
    public function actionIndex($tree_uuid = null, $filter_uuid = null)
    {
        $node = StorageTree::findOne(['tree_uuid' => $tree_uuid]);
        $parent = $node ? $node->parents(1)->one() : null;

        $settings = StorageSettings::loadSettings();
        $dataProvider = StorageTree::search($settings);

        $filter = StorageFilter::loadFilter($filter_uuid);
        $filter->buildQuery($dataProvider->query);

        $params = [
            'dataProvider' => $dataProvider,
            'settings' => $settings,
            'currentNode' => $node,
            'parentNode' => $parent,
            'isFiltered' => $filter->isActive
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param string $parent_uuid
     * @return string|array
     */
    public function actionCreate($parent_uuid = null)
    {
        $model = new Storage([
            'type' => Storage::STORAGE_TYPE_DIR,
            'locations' => [$parent_uuid]
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            // Set valid response format
            \Yii::$app->response->format = Response::FORMAT_JSON;

            // Validate user inputs
            $errors = ActiveForm::validate($model);

            if ($errors) {
                \Yii::$app->response->statusCode = 206;
                return $errors;
            }

            $model->save(false);

            return $model->attributes;
        }

        return $this->renderPartial('create', ['model' => $model, 'parent_uuid' => $parent_uuid]);
    }

    /**
     * @param $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        $model = Storage::findOne($uuid);

        if (!$model) {
            throw new HttpException(404);
        }

        if ($model->load(\Yii::$app->request->post())) {
            // Set valid response format
            \Yii::$app->response->format = Response::FORMAT_JSON;

            // Validate user inputs
            $errors = ActiveForm::validate($model);

            if ($errors) {
                \Yii::$app->response->statusCode = 206;
                return $errors;
            }

            $model->save(false);

            return $model->attributes;
        }

        return $this->renderPartial('edit', ['model' => $model]);
    }

    /**
     * Upload action
     * @param string $parent_uuid
     * @return array
     * @throws HttpException
     */
    public function actionUpload($parent_uuid = null)
    {
        $defaults = [
            'type' => Storage::STORAGE_TYPE_FILE,
            'locations' => $parent_uuid
        ];

        $model = new Storage($defaults);

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                return ['file_uuid' => $model->file->uuid];
            }

            return ['errors' => $model->errors];
        }

        throw new HttpException(400, 'Could not load user data');
    }

    /**
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = StorageTree::findAll(['tree_uuid' => $selected]);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param string $tree_uuid
     * @param string $filter_uuid
     * @return array|string
     */
    public function actionFilter($tree_uuid = null, $filter_uuid = null)
    {
        $model = StorageFilter::loadFilter($filter_uuid);

        if ($model->load(\Yii::$app->request->post())) {
            // Set valid response format
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $action = \Yii::$app->request->post('action');
            if ($action === self::ACTION_RESET) {
                $model->delete();
                return ['url' => Url::to(['index', 'tree_uuid' => $tree_uuid])];
            }

            // Validate user inputs
            $errors = ActiveForm::validate($model);

            if ($errors) {
                \Yii::$app->response->statusCode = 206;
                return $errors;
            }

            $model->save(false);

            return ['url' => Url::to(['index', 'tree_uuid' => $tree_uuid, 'filter_uuid' => $model->uuid])];
        }

        return $this->renderPartial('filter', ['model' => $model]);
    }

    /**
     * @return array|string
     */
    public function actionSettings()
    {
        $model = StorageSettings::loadSettings();

        if ($model->load(\Yii::$app->request->post())) {
            // Set valid response format
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $action = \Yii::$app->request->post('action');
            if ($action === self::ACTION_RESET) {
                return StorageSettings::reset();
            }

            // Validate user inputs
            $errors = ActiveForm::validate($model);

            if ($errors) {
                \Yii::$app->response->statusCode = 206;
                return $errors;
            }

            $model->save(false);

            return $model->attributes;
        }

        return $this->renderPartial('settings', ['model' => $model]);
    }

}
