<?php

namespace catalogs\modules\admin\modules\fields\controllers;

use catalogs\modules\admin\models\Catalog;
use catalogs\modules\admin\modules\fields\components\traits\Duplicator;
use catalogs\modules\admin\modules\fields\models\Field;
use catalogs\modules\admin\modules\fields\models\FieldValidator;
use catalogs\modules\admin\modules\fields\models\FieldValue;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class FieldsController
 *
 * @package catalogs\modules\admin\modules\fields\controllers
 */
class FieldsController extends \fields\controllers\FieldsController
{
    use Duplicator;
    /**
     * @var string|\yii\db\ActiveRecord
     */
    public $modelClass = Field::class;
    /**
     * @var string
     */
    public $validatorClass = FieldValidator::class;
    /**
     * @var string
     */
    public $valueClass = FieldValue::class;
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

            $this->setViewPath('@catalogs/modules/admin/modules/fields/views/fields');
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['create']['modelConfig'] = [
            'catalog_uuid' => \Yii::$app->request->get('catalog_uuid'),
            'type' => Field::FIELD_TYPE_DEFAULT,
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
        $params = [
            'dataProvider' => Field::search(['catalog_uuid' => $this->_catalog->uuid]),
            'catalog' => $this->_catalog,
        ];

        $relations = [
            'values' => FieldValue::class,
            'validators' => FieldValidator::class
        ];

        foreach ($relations as $name => $className) {
            $params[$name] = $className::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('field_uuid')
                ->indexBy('field_uuid')->column();
        }

        return $params;
    }
}
