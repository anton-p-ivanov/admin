<?php

namespace catalogs\controllers;

use app\models\User;
use catalogs\models\Element;
use catalogs\models\ElementTree;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Class LocationsController
 *
 * @package catalogs\controllers
 */
class LocationsController extends Controller
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

        return $isValid;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $behaviors['ajax'] = [
//            'class' => AjaxFilter::className(),
//        ];

        return $behaviors;
    }

    /**
     * @param string $tree_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($tree_uuid)
    {
        /* @var ElementTree $currentNode */
        $currentNode = ElementTree::findOne(['tree_uuid' => $tree_uuid]);

        if (!$currentNode) {
            throw new HttpException(404, 'Invalid tree node.');
        }

        $parentNode = $currentNode->isRoot() ? null : $currentNode->parents(1)->one();

        $dataProvider = ElementTree::search(['tree_uuid' => $tree_uuid]);
        $dataProvider->sort = false;
        $dataProvider->pagination->pageSize = 10;
        $dataProvider->query->andFilterWhere(['{{%catalogs_elements}}.[[type]]' => Element::ELEMENT_TYPE_SECTION]);

        $params = [
            'dataProvider' => $dataProvider,
            'currentNode' => $currentNode,
            'parentNode' => $parentNode
        ];

        return $this->renderPartial('index', $params);
    }
}