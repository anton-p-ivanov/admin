<?php

namespace forms\modules\fields\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use forms\modules\fields\models\Field;
use forms\modules\fields\models\FieldValidator;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class ValidatorsController
 * @package forms\modules\fields\controllers
 */
class ValidatorsController extends Controller
{
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
        ];

        return $behaviors;
    }

    /**
     * @param string $field_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($field_uuid)
    {
        $field = Field::findOne($field_uuid);

        if (!$field) {
            throw new HttpException(404);
        }

        return $this->renderPartial('index', [
            'field_uuid' => $field_uuid,
            'dataProvider' => FieldValidator::search($field_uuid),
        ]);
    }

    /**
     * @param string $field_uuid
     * @return array|string
     */
    public function actionCreate($field_uuid)
    {
        $model = new FieldValidator([
            'sort' => 100,
            'field_uuid' => $field_uuid
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        /* @var FieldValidator $model */
        $model = FieldValidator::findOne($uuid);

        if (!$model) {
            throw new HttpException(404);
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('edit', [
            'model' => $model,
        ]);
    }

    /**
     * @param string $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCopy($uuid)
    {
        /* @var FieldValidator $model */
        $model = FieldValidator::findOne($uuid);

        if (!$model) {
            throw new HttpException(404);
        }

        /* @var FieldValidator $copy */
        $copy = $model->duplicate();

        if ($copy->load(\Yii::$app->request->post())) {
            return $this->postCreate($copy);
        }

        return $this->renderPartial('copy', [
            'model' => $copy,
        ]);
    }

    /**
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = FieldValidator::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param FieldValidator $model
     * @return array
     */
    protected function postCreate(FieldValidator $model)
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
