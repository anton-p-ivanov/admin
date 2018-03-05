<?php

namespace forms\modules\admin\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use app\models\Workflow;
use fields\components\traits\Duplicator;
use forms\modules\admin\models\Form;
use forms\modules\admin\models\FormStatus;
use forms\modules\admin\modules\fields\models\Field;
use yii\base\InvalidConfigException;
use yii\filters\AjaxFilter;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class FormsController
 *
 * @package forms\modules\admin\controllers
 */
class FormsController extends Controller
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
        $behaviors['cn'] = [
            'class' => ContentNegotiator::class,
            'only' => ['templates'],
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ]
        ];

        return $behaviors;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $params = [
            'dataProvider' => Form::search(),
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @return array|string
     */
    public function actionCreate()
    {
        $model = new Form([
            'active' => true,
            'active_dates' => ['active_from_date' => \Yii::$app->formatter->asDatetime(date('Y-m-d H:i:s'))],
            'sort' => 100,
            'title' => \Yii::t('forms', 'New Web-form'),
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        // Format dates into human readable format
        $model->formatDatesArray(['active_from_date', 'active_to_date']);

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
        /* @var Form $model */
        $model = Form::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Form not found.');
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        // Format dates into human readable format
        $model->formatDatesArray(['active_from_date', 'active_to_date']);

        return $this->renderPartial('edit', [
            'model' => $model,
            'workflow' => $model->workflow ?: new Workflow()
        ]);
    }

    /**
     * @param string $uuid
     * @param bool $deepCopy
     * @return array|string
     * @throws HttpException
     */
    public function actionCopy($uuid, $deepCopy = false)
    {
        /* @var Form $model */
        $model = Form::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Form not found.');
        }

        // Makes a form`s copy
        $copy = $model->duplicate();

        if ($copy->load(\Yii::$app->request->post())) {
            return $this->postCreate($copy, $deepCopy ? $model : null);
        }

        // Format dates into human readable format
        $copy->formatDatesArray(['active_from_date', 'active_to_date']);

        return $this->renderPartial('copy', [
            'model' => $copy,
            'workflow' => $copy->workflow ?: new Workflow()
        ]);
    }

    /**
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = Form::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param string $type_uuid
     * @return array
     * @throws InvalidConfigException
     */
    public function actionTemplates($type_uuid)
    {
        /* @var \mail\models\Template $className */
        $className = '\mail\models\Template';

        if (!class_exists($className)) {
            throw new InvalidConfigException('Module `mail` seems to be missed.');
        }

        return $className::getList($type_uuid);
    }

    /**
     * @param string $form_uuid
     * @return string
     * @throws HttpException
     */
    public function actionHelp($form_uuid)
    {
        /* @var Form $model */
        $model = Form::findOne($form_uuid);

        if (!$model) {
            throw new HttpException(404, 'Form not found.');
        }

        $extra = [
            'FORM_FIELD_AUTH' => 'User authentication widget',
            'FORM_FIELD_CAPTCHA' => 'CAPTCHA widget',
        ];

        $fields = $model->getFields()
            ->filterWhere(['active' => true])
            ->orderBy(['sort' => SORT_ASC, 'code' => SORT_ASC, 'label' => SORT_DESC])
            ->select('label')
            ->indexBy('code')
            ->column();

        $fields = ArrayHelper::merge($fields, $extra);

        return $this->renderPartial('help', ['fields' => $fields]);
    }

    /**
     * @param Form $model
     * @param Form $original
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
            foreach ($original->statuses as $status) {
                $this->duplicateStatus($status, $model->uuid);
            }

            foreach ($original->fields as $field) {
                $this->duplicateField($field, $model->uuid);
            }
        }

        return $model->attributes;
    }

    /**
     * @param FormStatus $status
     * @param string $uuid
     * @return bool
     */
    protected function duplicateStatus($status, $uuid)
    {
        $clone = $status->duplicate();
        $clone->form_uuid = $uuid;

        return $clone->save();
    }

    /**
     * @param Field $field
     * @param string $uuid
     * @return bool
     */
    protected function duplicateField($field, $uuid)
    {
        $clone = $field->duplicate();
        $clone->form_uuid = $uuid;

        if ($clone->save()) {
            foreach ($field->fieldValidators as $validator) {
                $this->duplicateValidator($validator, $clone->uuid);
            }

            foreach ($field->fieldValues as $value) {
                $this->duplicateValue($value, $clone->uuid);
            }

            return true;
        }

        return false;
    }
}
