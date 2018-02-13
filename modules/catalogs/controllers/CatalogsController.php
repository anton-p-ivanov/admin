<?php

namespace catalogs\controllers;

use app\models\User;
use catalogs\models\Catalog;
use catalogs\models\Type;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class CatalogsController
 *
 * @package catalogs\controllers
 */
class CatalogsController extends Controller
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
     * @param string $type_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($type_uuid = null)
    {
        $type = Type::findOne($type_uuid);

        if (!$type) {
            throw new HttpException(404, 'Catalog`s type not found.');
        }

        $catalogs = Catalog::find()
            ->joinWith('translation')
            ->where([
                '{{%catalogs}}.[[type_uuid]]' => $type_uuid,
                '{{%catalogs}}.[[active]]' => true])
            ->orderBy([
                '{{%catalogs}}.[[sort]]' => SORT_ASC,
                '{{%catalogs_i18n}}.[[title]]' => SORT_ASC])
            ->all();

        $params = [
            'type' => $type,
            'catalogs' => $catalogs,
        ];

        return $this->render('index', $params);
    }
}
