<?php

namespace forms\modules\fields\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use forms\models\Form;
use forms\modules\fields\models\Field;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class FieldsController
 * @package forms\modules\fields\controllers
 */
class FieldsController extends Controller
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
     * @param string $form_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($form_uuid)
    {
        $form = Form::findOne($form_uuid);

        if (!$form) {
            throw new HttpException(404);
        }

        return $this->renderPartial('index', [
            'form_uuid' => $form_uuid,
            'dataProvider' => Field::search($form_uuid),
        ]);
    }

    /**
     * @param string $form_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate($form_uuid)
    {
        $model = new Field([
            'active' => true,
            'sort' => 100,
            'type' => Field::FIELD_TYPE_DEFAULT,
            'form_uuid' => $form_uuid,
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
     * @return boolean
     * @todo check whether values, validators and their workflow records are deleted with form.
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

    /**
     * @param Field $model
     * @return array
     */
    protected function postCreate(Field $model)
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
