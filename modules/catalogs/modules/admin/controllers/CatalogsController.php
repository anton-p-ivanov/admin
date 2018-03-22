<?php

namespace catalogs\modules\admin\controllers;

use app\components\actions\CopyAction;
use app\components\actions\CreateAction;
use app\components\actions\DeleteAction;
use app\components\actions\EditAction;
use app\components\actions\IndexAction;
use app\components\BaseController;
use catalogs\modules\admin\models\Catalog;
use catalogs\modules\admin\models\Type;
use catalogs\modules\admin\modules\fields\components\traits\Duplicator;
use catalogs\modules\admin\modules\fields\models\Field;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class CatalogsController
 *
 * @package catalogs\modules\admin\controllers
 */
class CatalogsController extends BaseController
{
    use Duplicator;
    /**
     * @var string
     */
    public $modelClass = Catalog::class;
    /**
     * @var Type
     */
    private $_type;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid && in_array($action->id, ['index', 'create'])) {
            if (!($type_uuid = \Yii::$app->request->get('type_uuid'))) {
                throw new BadRequestHttpException();
            }

            if ($type_uuid && !($this->_type = Type::findOne(['uuid' => $type_uuid]))) {
                throw new NotFoundHttpException('Type not found.');
            }
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'params' => [$this, 'getIndexParams']
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelConfig' => [
                    'active' => 1,
                    'sort' => 100,
                    'type_uuid' => $this->_type ? $this->_type->uuid : null
                ]
            ],
            'edit' => EditAction::class,
            'copy' => [
                'class' => CopyAction::class,
                'useDeepCopy' => (int) \Yii::$app->request->get('deep') === 1
            ],
            'delete' => DeleteAction::class,
        ];
    }

    /**
     * @return array
     */
    public function getIndexParams()
    {
        return [
            'type' => $this->_type,
            'dataProvider' => Catalog::search(['{{%catalogs}}.[[type_uuid]]' => $this->_type->uuid]),
            'fields' => Field::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('catalog_uuid')
                ->indexBy('catalog_uuid')->column(),
        ];
    }

    /**
     * @param Catalog $model
     * @param Catalog $original
     */
    public function afterCopy($model, $original)
    {
        foreach ($original->groups as $group) {
            $this->duplicateGroup($group, $model->uuid);
        }

        foreach ($original->fields as $field) {
            $this->duplicateField($field, $model->uuid);
        }
    }

    /**
     * @param string $uuid
     * @return \yii\db\ActiveRecord|Catalog
     */
    public function getModel($uuid)
    {
        return Catalog::find()->multilingual()->where(['uuid' => $uuid])->one();
    }
}
