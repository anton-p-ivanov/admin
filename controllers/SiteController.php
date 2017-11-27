<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * Class SiteController
 */
class SiteController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @param $view
     * @return string
     */
    public function actionWidget($view)
    {
        return $this->renderAjax("widgets/$view");
    }
}