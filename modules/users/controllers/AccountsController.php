<?php

namespace users\controllers;

use accounts\models\Account;
use app\components\behaviors\ConfirmFilter;
use users\models\User;
use users\models\UserAccount;
use yii\filters\AjaxFilter;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class AccountsController
 *
 * @package users\controllers
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
            'only' => ['list'],
            'formats' => ['application/json' => Response::FORMAT_JSON]
        ];

        return $behaviors;
    }

    /**
     * @param string $user_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($user_uuid)
    {
        $user = User::findOne($user_uuid);

        if (!$user) {
            throw new HttpException(404, 'User not found.');
        }

        $params = [
            'dataProvider' => UserAccount::search($user_uuid),
            'user' => $user
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param string $user_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate($user_uuid)
    {
        $user = User::findOne($user_uuid);

        if (!$user) {
            throw new HttpException(404, 'User not found.');
        }

        $model = new UserAccount([
            'user_uuid' => $user_uuid
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
        /* @var UserAccount $model */
        $model = UserAccount::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'User account not found.');
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
        /* @var UserAccount $model */
        $model = UserAccount::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'User account not found.');
        }

        // Makes a status copy
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
        $models = UserAccount::findAll($selected);
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
     * @param UserAccount $model
     * @return array
     */
    protected function postCreate(UserAccount $model)
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
