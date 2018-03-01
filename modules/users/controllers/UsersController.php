<?php

namespace users\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\Workflow;
use users\components\traits\Duplicator;
use users\models\User;
use users\models\UserAccount;
use users\models\UserPassword;
use users\models\UserRole;
use users\models\UserSite;
use yii\filters\AjaxFilter;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class UsersController
 * @package users\controllers
 */
class UsersController extends Controller
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
            'only' => ['list', 'get'],
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
        $dataProvider = User::search();

        $params = [
            'dataProvider' => $dataProvider,
            'accounts' => UserAccount::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('user_uuid')
                ->indexBy('user_uuid')->column(),
            'roles' => UserRole::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('user_id')
                ->indexBy('user_id')->column(),
            'sites' => UserSite::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('user_uuid')
                ->indexBy('user_uuid')->column(),
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
        $model = new User();

        $password = new UserPassword();
        $password->scenario = UserPassword::SCENARIO_NEW_USER;

        if ($model->load(\Yii::$app->request->post()) && $password->load(\Yii::$app->request->post())) {
            return $this->postCreate($model, $password);
        }

        return $this->renderPartial('create', [
            'model' => $model,
            'password' => $password,
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
        /* @var User $model */
        $model = User::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'User not found.');
        }

        $model->data = $model->getData();
        $password = new UserPassword(['user_uuid' => $model->uuid]);

        if ($model->load(\Yii::$app->request->post()) && $password->load(\Yii::$app->request->post())) {
            return $this->postCreate($model, $password);
        }

        return $this->renderPartial('edit', [
            'model' => $model,
            'password' => $password,
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
        /* @var User $model */
        $model = User::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'User not found.');
        }

        $password = new UserPassword();
        $password->scenario = UserPassword::SCENARIO_NEW_USER;

        // Makes a copy
        $copy = $model->duplicate();
        $copy->data = $model->getData();

        if ($copy->load(\Yii::$app->request->post()) && $password->load(\Yii::$app->request->post())) {
            return $this->postCreate($copy, $password, $deep ? $model : null);
        }

        return $this->renderPartial('copy', [
            'model' => $copy,
            'password' => $password,
            'workflow' => new Workflow()
        ]);
    }

    /**
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = User::findAll($selected);
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
        $query = User::find()->where(['like', 'CONCAT_WS(" ", `fname`, `lname`)', $search]);

        if (\Yii::$app->request->method === 'OPTIONS') {
            $count = $query->count();
            if ($count > 50) {
                return ['count' => $count];
            }
        }

        return $query
            ->select(['title' => 'CONCAT(`fname`, " ", `lname`, " (", `email`, ")")'])
            ->indexBy('uuid')
            ->orderBy('title')
            ->column();
    }

    /**
     * @param string $uuid
     * @return array|null|\yii\db\ActiveRecord
     */
    public function actionGet($uuid)
    {
        return User::find()->where(['uuid' => $uuid])->one();
    }

    /**
     * @param User $model
     * @param User $original
     * @param UserPassword $password
     * @return array
     */
    protected function postCreate($model, $password, $original = null)
    {
        // Validate user inputs
        $errors = ArrayHelper::merge(
            ActiveForm::validate($model),
            ActiveForm::validate($password)
        );

        if ($errors) {
            \Yii::$app->response->statusCode = 206;
            return $errors;
        }

        $result = $model->save(false);

        if ($result && $password->password_new) {
            $password->user_uuid = $model->uuid;
            $result = $result && $password->save(false);
        }

        if ($result && $original) {
            $this->duplicateUser($original, $model->uuid);
        }

        return $model->attributes;
    }

    /**
     * @param User $original
     * @param $uuid
     */
    protected function duplicateUser($original, $uuid)
    {
        $relations = ['account', 'role', 'site'];

        foreach ($relations as $relation) {
            foreach ($original->{'user' . ucfirst($relation) . 's'} as $model) {
                $this->{'duplicate' . ucfirst($relation)}($model, $uuid);
            }
        }
    }
}
