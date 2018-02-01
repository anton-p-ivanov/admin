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
            'validators' => FieldValidator::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('field_uuid')
                ->indexBy('field_uuid')->column(),
            'values' => FieldValue::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('field_uuid')
                ->indexBy('field_uuid')->column(),
        ];

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
//
//    /**
//     * @param string $uuid
//     * @return array|string
//     * @throws HttpException
//     */
//    public function actionEdit($uuid)
//    {
//        /* @var Field $model */
//        $model = Field::findOne($uuid);
//
//        if (!$model) {
//            throw new HttpException(404, 'Field not found.');
//        }
//
//        if ($model->load(\Yii::$app->request->post())) {
//            return $this->postCreate($model);
//        }
//
//        return $this->renderPartial('edit', [
//            'model' => $model,
//            'workflow' => $model->workflow ?: new Workflow()
//        ]);
//    }
//
//    /**
//     * @param string $uuid
//     * @param bool $deep
//     * @return array|string
//     * @throws HttpException
//     */
//    public function actionCopy($uuid, $deep = false)
//    {
//        /* @var Field $model */
//        $model = Field::findOne($uuid);
//
//        if (!$model) {
//            throw new HttpException(404, 'Field not found.');
//        }
//
//        // Makes a model`s copy
//        /* @var Field $copy */
//        $copy = $model->duplicate();
//
//        if ($copy->load(\Yii::$app->request->post())) {
//            return $this->postCreate($copy, $deep ? $model : null);
//        }
//
//        return $this->renderPartial('copy', [
//            'model' => $copy,
//            'workflow' => new Workflow()
//        ]);
//    }
//
//    /**
//     * @return boolean
//     */
//    public function actionDelete()
//    {
//        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
//        $models = Field::findAll($selected);
//        $counter = 0;
//
//        foreach ($models as $model) {
//            $counter += (int) $model->delete();
//        }
//
//        return $counter === count($models);
//    }
//
//    /**
//     * @param Field $model
//     * @param Field $original
//     * @return array
//     */
//    protected function postCreate($model, $original = null)
//    {
//        // Validate user inputs
//        $errors = ActiveForm::validate($model);
//
//        if ($errors) {
//            \Yii::$app->response->statusCode = 206;
//            return $errors;
//        }
//
//        $result = $model->save(false);
//
//        if ($result && $original) {
//            foreach ($original->fieldValues as $value) {
//                $this->duplicateValue($value, $model->uuid);
//            }
//
//            foreach ($original->fieldValidators as $validator) {
//                $this->duplicateValidator($validator, $model->uuid);
//            }
//        }
//
//        return $model->attributes;
//    }
}
