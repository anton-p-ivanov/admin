<?php

namespace forms\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use app\models\Workflow;
use forms\models\Form;
use forms\models\FormResult;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class ResultsController
 *
 * @package forms\controllers
 */
class ResultsController extends Controller
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
            'except' => ['index']
        ];

        return $behaviors;
    }

    /**
     * @param string $form_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($form_uuid)
    {
        $form = Form::findOne($form_uuid);

        if (!$form) {
            throw new HttpException(404, 'Form not found.');
        }

        $params = [
            'dataProvider' => FormResult::search($form_uuid),
            'form' => $form
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param string $form_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate($form_uuid)
    {
        /* @var Form $form */
        $form = Form::findOne($form_uuid);

        if (!$form) {
            throw new HttpException(404, 'Form not found.');
        }

        /* @var \forms\models\FormStatus $status */
        $status = $form->getDefaultStatus();

        $model = new FormResult([
            'form_uuid' => $form_uuid,
            'status_uuid' => $status ? $status->uuid : null
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
     * @param $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        /* @var FormResult $model */
        $model = FormResult::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Result not found.');
        }

        $model->data = Json::decode($model->data);

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
        /* @var FormResult $model */
        $model = FormResult::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Result not found.');
        }

        // Makes a status copy
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
        $models = FormResult::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param FormResult $model
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
