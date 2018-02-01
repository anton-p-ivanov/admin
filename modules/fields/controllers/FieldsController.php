<?php

namespace fields\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use app\models\Workflow;
use fields\models\Field;
use fields\models\FieldValidator;
use fields\models\FieldValue;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class FieldsController
 *
 * @package fields\controllers
 */
abstract class FieldsController extends Controller
{
    /**
     * @var string|\yii\db\ActiveRecord
     */
    public $modelClass = Field::class;

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
     * @return string
     */
    abstract public function actionIndex();

    /**
     * @return array|string
     */
    public function actionCreate()
    {
        /* @var Field $model */
        $model = new $this->modelClass([
            'active' => true,
            'sort' => 100,
            'type' => Field::FIELD_TYPE_DEFAULT,
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
        /* @var Field $model */
        $model = $this->modelClass::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Field not found.');
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
        /* @var Field $model */
        $model = $this->modelClass::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Field not found.');
        }

        // Makes a form`s copy
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
        $models = $this->modelClass::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param Field $model
     * @param Field $original
     * @return array
     */
    protected function postCreate(Field $model, $original = null)
    {
        // Validate user inputs
        $errors = ActiveForm::validate($model);

        if ($errors) {
            \Yii::$app->response->statusCode = 206;
            return $errors;
        }

        $result = $model->save(false);

        if ($result && $original) {
            foreach ($original->fieldValues as $value) {
                $this->duplicateValue($value, $model->uuid);
            }

            foreach ($original->fieldValidators as $validator) {
                $this->duplicateValidator($validator, $model->uuid);
            }
        }

        return $model->attributes;
    }

    /**
     * @param FieldValidator $validator
     * @param string $uuid
     * @return bool
     */
    protected function duplicateValidator(FieldValidator $validator, $uuid)
    {
        $clone = $validator->duplicate();
        $clone->field_uuid = $uuid;

        return $clone->save();
    }

    /**
     * @param FieldValue $value
     * @param string $uuid
     * @return bool
     */
    protected function duplicateValue(FieldValue $value, $uuid)
    {
        $clone = $value->duplicate();
        $clone->field_uuid = $uuid;

        return $clone->save();
    }
}
