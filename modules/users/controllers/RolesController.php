<?php

namespace users\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\actions\IndexAction;
use app\components\BaseController;
use users\models\User;
use users\models\UserRole;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class RolesController
 *
 * @package users\controllers
 */
class RolesController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = UserRole::class;
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
                    'user_id' => \Yii::$app->request->get('user_uuid'),
                    'valid_dates' => ['valid_from_date' => \Yii::$app->formatter->asDatetime(date('Y-m-d H:i:s'))],
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
            'dataProvider' => UserRole::search(['user_id' => $this->_user->uuid]),
            'user' => $this->_user
        ];
    }

    /**
     * @param UserRole $model
     */
    public function beforeRender($model)
    {
        // Format dates into human readable format
        $model->formatDatesArray();
    }
}
