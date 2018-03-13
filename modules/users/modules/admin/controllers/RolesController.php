<?php

namespace users\modules\admin\controllers;

use app\components\BaseController;
use users\modules\admin\models\Role;

/**
 * Class RolesController
 *
 * @package users\modules\admin\controllers
 */
class RolesController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Role::class;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['create']['modelConfig'] = [
            'type' => 1
        ];

        return $actions;
    }

    /**
     * @param string $name
     * @return \yii\db\ActiveRecord
     */
    public function getModel($name)
    {
        return Role::find()->multilingual()->where(['name' => $name])->one();
    }
}
