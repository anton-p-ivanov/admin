<?php

namespace users\controllers;

use accounts\models\Account;
use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\actions\IndexAction;
use app\components\BaseController;
use users\models\User;
use users\models\UserAccount;
use yii\filters\ContentNegotiator;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class AccountsController
 *
 * @package users\controllers
 */
class AccountsController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = UserAccount::class;
    /**
     * @var User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid && in_array($action->id, ['index', 'create'])) {
            if (!($user_uuid = \Yii::$app->request->get('user_uuid'))) {
                throw new BadRequestHttpException();
            }

            if (!($this->_user = User::findOne(['uuid' => $user_uuid]))) {
                throw new NotFoundHttpException('User not found.');
            }
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['cn'] = [
            'class' => ContentNegotiator::class,
            'only' => ['list'],
            'formats' => ['application/json' => Response::FORMAT_JSON]
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'params' => [$this, 'getIndexParams']
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelConfig' => [
                    'user_uuid' => \Yii::$app->request->get('user_uuid')
                ]
            ],
            'edit' => EditAction::class,
            'copy' => CopyAction::class,
            'delete' => DeleteAction::class,
        ];
    }

    /**
     * @return array
     */
    public function getIndexParams()
    {
        return [
            'dataProvider' => UserAccount::search(['user_uuid' => $this->_user->uuid]),
            'user' => $this->_user
        ];
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
}
