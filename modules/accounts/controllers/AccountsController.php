<?php

namespace accounts\controllers;

use accounts\models\Account;
use accounts\models\AccountFilter;
use accounts\models\AccountSettings;
use app\components\behaviors\ConfirmFilter;
use app\models\User;
use app\models\Workflow;
use yii\filters\AjaxFilter;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class AccountsController
 * @package accounts\controllers
 */
class AccountsController extends Controller
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
        $behaviors['cn'] = [
            'class' => ContentNegotiator::className(),
            'only' => ['list'],
            'formats' => ['application/json' => Response::FORMAT_JSON]
        ];

        return $behaviors;
    }

    /**
     * @param string|null $filter_uuid
     * @return string
     */
    public function actionIndex($filter_uuid = null)
    {
        $filter = null;
        $settings = AccountSettings::loadSettings();
        $dataProvider = Account::search($settings);

        if ($filter_uuid) {
            $filter = AccountFilter::loadFilter($filter_uuid);
            $filter->buildQuery($dataProvider->query);
        }

        $params = [
            'dataProvider' => $dataProvider,
            'settings' => $settings,
            'isFiltered' => $filter ? $filter->isActive : false,
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
        $model = new Account([
            'active' => true,
            'title' => 'test',
            'web' => 'www.test.ru',
            'email' => 'test@test.ru'
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            $result = $this->postCreate($model);

            if (array_key_exists('uuid', $result)) {
                // Set flash message
                \Yii::$app->session->setFlash('ACCOUNT_CREATED');

                // Return edit URL
                return ['url' => Url::to(['edit', 'uuid' => $result['uuid']])];
            }

            return $result;
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
        /* @var Account $model */
        $model = Account::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Account not found.');
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
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = Account::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * This method searches for accounts by their name or its part.
     * Used in select boxes.
     *
     * @param string $search
     * @return array
     */
    public function actionList($search = '')
    {
        $query = Account::find()->where(['like', 'title', $search]);

        if (\Yii::$app->request->method === 'OPTIONS') {
            $count = $query->count();
            if ($count > 50) {
                return ['count' => $count];
            }
        }

        return $query
            ->select('title')
            ->indexBy('uuid')
            ->orderBy('title')
            ->column();
    }

    /**
     * @param Account $model
     * @return array
     */
    protected function postCreate(Account &$model)
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
