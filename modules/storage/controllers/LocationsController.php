<?php

namespace storage\controllers;

use app\models\User;
use storage\models\Storage;
use storage\models\StorageTree;
use yii\filters\AjaxFilter;
use yii\web\Controller;

/**
 * Class LocationsController
 * @package storage\controllers
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
        $behaviors['ajax'] = [
            'class' => AjaxFilter::class,
        ];

        return $behaviors;
    }

    /**
     * @param string $tree_uuid
     * @param bool $withFiles
     * @return string
     */
    public function actionIndex($tree_uuid = null, $withFiles = false)
    {
        $node = StorageTree::findOne(['tree_uuid' => $tree_uuid]);
        $parent = $node ? $node->parents(1)->one() : null;

        $dataProvider = StorageTree::search();
        $dataProvider->sort = false;
        $dataProvider->pagination->pageSize = 50;

        if (!$withFiles) {
            $dataProvider->query->andFilterWhere(['{{%storage}}.[[type]]' => Storage::STORAGE_TYPE_DIR]);
        }

        $params = [
            'dataProvider' => $dataProvider,
            'currentNode' => $node,
            'parentNode' => $parent,
            'withFiles' => $withFiles
        ];

        return $this->renderPartial('index', $params);
    }
}