<?php

namespace fields\controllers;

use app\components\BaseController;
use fields\models\Group;

/**
 * Class GroupsController
 *
 * @package fields\controllers
 */
class GroupsController extends BaseController
{
    /**
     * @var Group
     */
    public $modelClass = Group::class;

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
