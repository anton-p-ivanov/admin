<?php

namespace storage\controllers;

use app\components\behaviors\ConfirmFilter;
use storage\models\Storage;
use storage\models\StorageFile;
use storage\models\StorageVersion;
use yii\data\ActiveDataProvider;
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
    public function actions()
    {
        return [
            'delete' => [
                'class' => 'app\actions\DeleteAction',
                'modelClass' => StorageFile::className()
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
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['delete'],
                'upload' => ['put'],
                'activate' => ['post']
            ]
        ];
        $behaviors['cn'] = [
            'class' => ContentNegotiator::className(),
            'only' => ['upload'],
            'formats' => ['application/json' => Response::FORMAT_JSON]
        ];
        $behaviors['confirm'] = [
            'class' => ConfirmFilter::className(),
            'actions' => ['delete', 'activate']
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
            throw new HttpException(404);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getVersions(),
            'pagination' => ['defaultPageSize' => 5],
            'sort' => false
        ]);

        return $this->renderPartial('index', ['dataProvider' => $dataProvider, 'storage_uuid' => $model->uuid]);
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
}
