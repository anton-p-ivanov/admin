<?php

namespace accounts\modules\admin\controllers;

use accounts\modules\admin\models\AccountType;
use accounts\modules\admin\models\Type;
use app\components\actions\IndexAction;
use app\components\BaseController;

/**
 * Class TypesController
 *
 * @package accounts\modules\admin\controllers
 */
class TypesController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Type::class;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index'] = [
            'class' => IndexAction::class,
            'params' => [$this, 'getIndexParams']
        ];

        return $actions;
    }

    /**
     * @return array
     */
    public function getIndexParams()
    {
        return [
            'dataProvider' => Type::search(),
            'accounts' => AccountType::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('type_uuid')
                ->indexBy('type_uuid')->column()
        ];
    }

    /**
     * @param string $uuid
     * @return \yii\db\ActiveRecord
     */
    public function getModel($uuid)
    {
        return Type::find()->multilingual()->where(['uuid' => $uuid])->one();
    }
}
