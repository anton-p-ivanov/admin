<?php

namespace admin\controllers;

use admin\models\Site;
use app\components\BaseController;

/**
 * Class SitesController
 *
 * @package admin\controllers
 */
class SitesController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Site::class;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['create']['modelConfig'] = [
            'active' => true,
            'sort' => 100
        ];

        return $actions;
    }
}
