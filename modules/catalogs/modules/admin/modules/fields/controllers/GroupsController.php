<?php

namespace catalogs\modules\admin\modules\fields\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use catalogs\modules\admin\models\Catalog;
use catalogs\modules\admin\modules\fields\models\Group;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class GroupsController
 *
 * @package catalogs\modules\admin\modules\fields\controllers
 */
class GroupsController extends Controller
{
    /**
     * @var string
     */
    public $modelClass = Group::class;
    /**
     * @var array
     */
    public $viewParams = [];

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws HttpException
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

        if ($isValid && in_array($action->id, ['index', 'create'])) {
            $catalog = Catalog::find()
                ->where(['uuid' => \Yii::$app->request->get('catalog_uuid')])
                ->multilingual()
                ->one();

            if (!$catalog) {
                throw new HttpException(404, 'Catalog not found.');
            }

            $this->viewParams['catalog'] = $catalog;
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
     * @return array
     */
    public function actions()
    {
        return [
            'index' => '\app\components\actions\IndexAction',
            'create' => [
                'class' => '\app\components\actions\CreateAction',
                'modelConfig' => [
                    'catalog_uuid' => \Yii::$app->request->get('catalog_uuid'),
                    'active' => true,
                    'sort' => 100
                ]
            ],
            'edit' => '\app\components\actions\EditAction',
            'copy' => '\app\components\actions\CopyAction',
            'delete' => '\app\components\actions\DeleteAction',
        ];
    }
}
