<?php

namespace forms\controllers;

use app\components\actions\IndexAction;
use app\components\BaseController;
use forms\models\Form;
use forms\models\Result;

/**
 * Class FormsController
 *
 * @package forms\controllers
 */
class FormsController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Form::class;

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'params' => [$this, 'getIndexParams']
            ]
        ];
    }

    /**
     * @return array
     */
    public function getIndexParams()
    {
        return [
            'dataProvider' => Form::search(),
            'results' => Result::find()
                ->select('COUNT(*)')
                ->groupBy('form_uuid')
                ->indexBy('form_uuid')
                ->column()
        ];
    }
}
