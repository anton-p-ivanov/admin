<?php

namespace accounts\controllers;

use app\models\AddressCountry;
use app\models\User;
use yii\filters\AjaxFilter;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class CountriesController
 * @package accounts\controllers
 */
class CountriesController extends Controller
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
        $behaviors['ajax'] = [
            'class' => AjaxFilter::className(),
        ];
        $behaviors['cn'] = [
            'class' => ContentNegotiator::className(),
            'only' => ['list'],
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ]
        ];

        return $behaviors;
    }


    /**
     * This method searches for countries by their name or its part.
     * Used in select boxes.
     *
     * @param string $search
     * @param string $lang
     * @return array
     */
    public function actionList($search = '', $lang = '')
    {
        $titleField = 'title';

        if ($lang) {
            $titleField .= '_' . $lang;
        }

        $query = AddressCountry::find()->where(['like', $titleField, trim($search)]);

        if (\Yii::$app->request->method === 'OPTIONS') {
            $count = $query->count();
            if ($count > 50) {
                return ['count' => $count];
            }
        }

        return $query
            ->select($titleField)
            ->indexBy('code')
            ->orderBy($titleField)
            ->column();
    }
}
