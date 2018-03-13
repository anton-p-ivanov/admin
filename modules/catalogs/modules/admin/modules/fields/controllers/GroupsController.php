<?php

namespace catalogs\modules\admin\modules\fields\controllers;

use catalogs\modules\admin\models\Catalog;
use catalogs\modules\admin\modules\fields\models\Group;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class GroupsController
 *
 * @package catalogs\modules\admin\modules\fields\controllers
 */
class GroupsController extends \fields\controllers\GroupsController
{
    /**
     * @var Group
     */
    public $modelClass = Group::class;
    /**
     * @var Catalog
     */
    private $_catalog;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid) {
            if (in_array($action->id, ['index', 'create'])) {
                if (!($catalog_uuid = \Yii::$app->request->get('catalog_uuid'))) {
                    throw new BadRequestHttpException();
                }

                if (!($this->_catalog = Catalog::find()->where(['uuid' => $catalog_uuid])->multilingual()->one())) {
                    throw new NotFoundHttpException('Catalog not found.');
                }
            }

            $this->setViewPath('@catalogs/modules/admin/modules/fields/views/groups');
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['params'] = [$this, 'getIndexParams'];
        $actions['create']['modelConfig'] = [
            'catalog_uuid' => \Yii::$app->request->get('catalog_uuid'),
            'active' => true,
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
            'dataProvider' => Group::search(['catalog_uuid' => $this->_catalog->uuid]),
            'catalog' => $this->_catalog,
        ];
    }
}
