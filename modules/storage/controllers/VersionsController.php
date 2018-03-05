<?php

namespace storage\controllers;

use app\components\behaviors\ConfirmFilter;
use storage\models\Storage;
use storage\models\StorageFile;
use storage\models\StorageVersion;
use yii\data\ActiveDataProvider;
use yii\filters\AjaxFilter;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class VersionsController
 * @package storage\controllers
 */
class VersionsController extends Controller
{
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
                'upload' => ['put'],
                'activate' => ['post']
            ]
        ];
        $behaviors['cn'] = [
            'class' => ContentNegotiator::class,
            'only' => ['upload'],
            'formats' => ['application/json' => Response::FORMAT_JSON]
        ];
        $behaviors['confirm'] = [
            'class' => ConfirmFilter::class,
            'actions' => ['delete', 'activate']
        ];
        $behaviors['ajax'] = [
            'class' => AjaxFilter::class,
            'except' => ['index']
        ];

        return $behaviors;
    }

    /**
     * @param string $storage_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex(string $storage_uuid)
    {
        $model = Storage::findOne($storage_uuid);

        if (!$model) {
            throw new HttpException(404, 'Storage element not found.');
        }

        $currentNode = $model->tree[0];
        $parentNode = $currentNode ? $currentNode->parents(1)->one() : null;

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getVersions(),
            'sort' => false
        ]);

        $params = [
            'dataProvider' => $dataProvider,
            'storage' => $model,
            'currentNode' => $currentNode,
            'parentNode' => $parentNode
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * Upload action
     * @param string $storage_uuid
     * @return array
     * @throws HttpException
     */
    public function actionUpload($storage_uuid)
    {
        $storage = Storage::findOne($storage_uuid);

        if (!$storage) {
            throw new HttpException(404);
        }

        $model = new StorageFile();
        $model->storage_uuid = $storage_uuid;

        $files = \Yii::$app->request->post('Storage');

        if ($model->load(Json::decode($files['files'])[0], '')) {
            if ($model->save()) {
                // Update Storage::$title attribute to StorageFile::$name
                $storage->updateAttributes(['title' => $model->name]);

                return ['file_uuid' => $model->uuid];
            }

            return ['errors' => $model->errors];
        }

        throw new HttpException(400, 'Could not load user data');
    }

    /**
     * @param $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        $model = StorageFile::findOne($uuid);
        $model->scenario = StorageFile::SCENARIO_RENAME;

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

            if ($model->save(false)) {
                $version = StorageVersion::findOne(['file_uuid' => $uuid]);
                if ($version->isActive()) {
                    $version->storage->updateAttributes(['title' => $model->name]);
                }
            }

            return $model->attributes;
        }

        return $this->renderPartial('edit', ['model' => $model]);
    }

    /**
     * @param $uuid
     * @return string
     * @throws HttpException
     */
    public function actionActivate($uuid)
    {
        $version = StorageVersion::findOne(['file_uuid' => $uuid]);

        if (!$version) {
            throw new HttpException(404);
        }

        $version->active = true;

        if ($version->save()) {
            // Update Storage::$title attribute to StorageFile::$name
            $version->storage->updateAttributes(['title' => $version->file->name]);

            return true;
        }

        return false;
    }

    /**
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = StorageFile::findAll(['uuid' => $selected]);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }
}
