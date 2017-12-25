<?php

namespace accounts\controllers;

use accounts\models\Account;
use accounts\models\AccountAddress;
use accounts\models\Address;
use app\components\behaviors\ConfirmFilter;
use app\models\AddressType;
use app\models\User;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class AddressesController
 * @package accounts\controllers
 */
class AddressesController extends Controller
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
     * @param string $account_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($account_uuid)
    {
        $account = Account::findOne($account_uuid);

        if (!$account) {
            throw new HttpException(404, 'Account not found.');
        }

        return $this->renderPartial('index', [
            'dataProvider' => AccountAddress::search($account_uuid),
            'account' => $account
        ]);
    }

    /**
     * @param string $account_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate($account_uuid)
    {
        /* @var Account $account */
        $account = Account::findOne($account_uuid);

        if (!$account) {
            throw new HttpException(404, 'Account not found.');
        }

        $defaultType = AddressType::getDefault();

        $model = new Address([
            'account_uuid' => $account_uuid,
            'type_uuid' => $defaultType ? $defaultType->uuid : null,
            'country_code' => mb_strtoupper(mb_substr(\Yii::$app->language, 0, 2))
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param string $uuid
     * @param string $account_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid, $account_uuid)
    {
        /* @var Address $model */
        $model = Address::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Address not found.');
        }

        $account = Account::findOne($account_uuid);
        if (!$account) {
            throw new HttpException(404, 'Account not found.');
        }

        $model->{'account_uuid'} = $account_uuid;
        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('edit', [
            'model' => $model,
        ]);
    }

    /**
     * @param string $uuid
     * @param string $account_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCopy($uuid, $account_uuid)
    {
        /* @var Address $model */
        $model = Address::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Address not found.');
        }

        $account = Account::findOne($account_uuid);
        if (!$account) {
            throw new HttpException(404, 'Account not found.');
        }

        // Makes a status copy
        $copy = $model->duplicate();
        $copy->{'account_uuid'} = $account_uuid;

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
        $models = Address::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param \yii\db\ActiveRecord $model
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
