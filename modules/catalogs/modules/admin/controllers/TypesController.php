<?php

namespace catalogs\modules\admin\controllers;

use app\components\BaseController;
use catalogs\modules\admin\models\Catalog;
use catalogs\modules\admin\models\Type;

/**
 * Class TypesController
 * @package catalogs\modules\admin\controllers
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
        $actions['index']['params'] = [$this, 'getIndexParams'];
        $actions['create']['modelConfig'] = [
            'sort' => 100
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
            'catalogs' => Catalog::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('type_uuid')
                ->indexBy('type_uuid')->column()
        ];
    }

    /**
     * @param string $uuid
     * @return \yii\db\ActiveRecord|Type
     */
    public function getModel($uuid)
    {
        return Type::find()->multilingual()->where(['uuid' => $uuid])->one();
    }
}
