<?php

namespace partnership\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use partnership\models\Status;
use partnership\models\StatusDiscount;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class DiscountsController
 *
 * @package partnership\controllers
 */
class DiscountsController extends Controller
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
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['delete'],
            ]
        ];
        $behaviors['confirm'] = [
            'class' => ConfirmFilter::class,
            'actions' => ['delete']
        ];
        $behaviors['ajax'] = [
            'class' => AjaxFilter::class,
            'except' => ['index']
        ];

        return $behaviors;
    }

    /**
     * @param string $status_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($status_uuid)
    {
        $status = Status::findOne($status_uuid);

        if (!$status) {
            throw new HttpException(404, 'Status not found.');
        }

        $params = [
            'dataProvider' => StatusDiscount::search($status_uuid),
            'status' => $status
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param string $status_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate($status_uuid)
    {
        $status = Status::findOne($status_uuid);

        if (!$status) {
            throw new HttpException(404, 'Status not found.');
        }

        $model = new StatusDiscount([
            'status_uuid' => $status_uuid,
            'dates' => ['issue_date' => \Yii::$app->formatter->asDatetime(date('Y-m-d H:i:s'))],
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        // Format dates into human readable format
        $model->formatDatesArray(['issue_date', 'expire_date']);

        return $this->renderPartial('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param string $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        /* @var StatusDiscount $model */
        $model = StatusDiscount::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Status discount not found.');
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        // Format dates into human readable format
        $model->formatDatesArray(['issue_date', 'expire_date']);

        // Format percent value
        $model->value = (double) $model->value * 100;

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
        /* @var StatusDiscount $model */
        $model = StatusDiscount::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Status discount not found.');
        }

        // Makes a status copy
        $copy = $model->duplicate();

        if ($copy->load(\Yii::$app->request->post())) {
            return $this->postCreate($copy);
        }

        // Format dates into human readable format
        $copy->formatDatesArray(['issue_date', 'expire_date']);

        // Format percent value
        $copy->value = (double) $copy->value * 100;

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
        $models = StatusDiscount::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param StatusDiscount $model
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
