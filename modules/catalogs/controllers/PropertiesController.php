<?php

namespace catalogs\controllers;

use catalogs\models\Element;
use catalogs\models\ElementField;
use catalogs\models\Property;
use catalogs\modules\admin\modules\fields\models\Field;
use yii\helpers\Json;
use yii\web\HttpException;

/**
 * Class PropertiesController
 *
 * @package catalogs\controllers
 */
class PropertiesController extends \fields\controllers\PropertiesController
{
    /**
     * @param string $element_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($element_uuid)
    {
        $element = Element::findOne($element_uuid);

        if (!$element) {
            throw new HttpException(404, 'Element not found.');
        }

        $dataProvider = Property::search(['catalog_uuid' => $element->catalog_uuid]);

        $params = [
            'dataProvider' => $dataProvider,
            'element' => $element,
            'properties' => ElementField::find()
                ->where(['element_uuid' => $element_uuid])
                ->joinWith('field')
                ->indexBy('field_uuid')
                ->all(),
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param string $element_uuid
     * @param string $field_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($element_uuid, $field_uuid)
    {
        $element = Element::findOne($element_uuid);
        if (!$element) {
            throw new HttpException(404, 'Invalid element identifier.');
        }

        $field = Field::findOne(['uuid' => $field_uuid, 'catalog_uuid' => $element->catalog_uuid]);
        if (!$field) {
            throw new HttpException(404, 'Invalid field identifier.');
        }

        $params = [
            'element_uuid' => $element_uuid,
            'field_uuid' => $field_uuid,
        ];

        /* @var ElementField $model */
        $model = ElementField::findOne($params) ?: new ElementField($params);

        if ($field->isMultiple()) {
            $model->value = Json::decode($model->value);
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postUpdate($model);
        }

        return $this->renderPartial('edit', [
            'model' => $model,
        ]);
    }
}
