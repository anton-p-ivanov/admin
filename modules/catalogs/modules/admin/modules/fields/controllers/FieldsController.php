<?php

namespace catalogs\modules\admin\modules\fields\controllers;

use app\models\Workflow;
use catalogs\modules\admin\models\Catalog;
use catalogs\modules\admin\modules\fields\components\traits\Duplicator;
use catalogs\modules\admin\modules\fields\models\Field;
use catalogs\modules\admin\modules\fields\models\FieldValidator;
use catalogs\modules\admin\modules\fields\models\FieldValue;
use yii\web\HttpException;

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
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if ($isValid) {
            $this->setViewPath('@catalogs/modules/admin/modules/fields/views/fields');
        }

        return $isValid;
    }

    /**
     * @return string
     * @throws HttpException
     */
    public function actionIndex()
    {
        $catalog_uuid = \Yii::$app->request->get('catalog_uuid');

        if (!$catalog_uuid) {
            throw new HttpException(400, 'Parameter `catalog_uuid` must be set.');
        }

        $catalog = Catalog::find()->where(['uuid' => $catalog_uuid])->multilingual()->one();

        if (!$catalog) {
            throw new HttpException(404, 'Catalog not found.');
        }

        $params = [
            'dataProvider' => Field::search(['catalog_uuid' => $catalog_uuid]),
            'catalog' => $catalog,
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

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate()
    {
        $catalog_uuid = \Yii::$app->request->get('catalog_uuid');

        if (!$catalog_uuid) {
            throw new HttpException(400, 'Parameter `catalog_uuid` must be set.');
        }

        $catalog = Catalog::find()->where(['uuid' => $catalog_uuid])->multilingual()->one();

        if (!$catalog) {
            throw new HttpException(404, 'Catalog not found.');
        }

        $model = new Field([
            'catalog_uuid' => $catalog_uuid,
            'type' => Field::FIELD_TYPE_DEFAULT,
            'active' => true,
            'sort' => 100
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('create', [
            'model' => $model,
            'workflow' => new Workflow()
        ]);
    }
}
