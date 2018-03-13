<?php

namespace accounts\controllers;

use accounts\models\Account;
use accounts\models\AccountDiscount;
use accounts\models\AccountStatus;
use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\actions\IndexAction;
use app\components\BaseController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class StatusesController
 *
 * @package accounts\controllers
 */
class StatusesController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = AccountStatus::class;
    /**
     * @var Account
     */
    private $_account;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid && in_array($action->id, ['index', 'create'])) {
            if (!($account_uuid = \Yii::$app->request->get('account_uuid'))) {
                throw new BadRequestHttpException();
            }

            if (!($this->_account = Account::findOne($account_uuid))) {
                throw new NotFoundHttpException('Account not found.');
            }
        }

        return $isValid;
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
                    'account_uuid' => \Yii::$app->request->get('account_uuid'),
                    'dates' => ['issue_date' => \Yii::$app->formatter->asDatetime(date('Y-m-d H:i:s'))],
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
            'dataProvider' => AccountStatus::search([
                'account_uuid' => $this->_account->uuid
            ]),
            'account' => $this->_account,
            'discounts' => AccountDiscount::find()
                ->select('COUNT(*)')
                ->indexBy('status_uuid')
                ->groupBy('status_uuid')
                ->column(),
        ];
    }

    /**
     * @param AccountStatus $model
     */
    public function beforeRender($model)
    {
        // Format dates into human readable format
        $model->formatDatesArray(['issue_date', 'expire_date']);
    }
}
