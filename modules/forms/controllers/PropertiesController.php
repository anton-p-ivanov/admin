<?php

namespace forms\controllers;

use forms\models\Result;
use forms\models\ResultProperty;
use forms\modules\admin\modules\fields\models\Field;
use yii\helpers\Json;
use yii\web\HttpException;

/**
 * Class PropertiesController
 *
 * @package forms\controllers
 */
class PropertiesController extends \fields\controllers\PropertiesController
{
    /**
     * @param string $result_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($result_uuid)
    {
        $result = Result::findOne($result_uuid);

        if (!$result) {
            throw new HttpException(404, 'Form result not found.');
        }

        $dataProvider = Field::search(['form_uuid' => $result->form_uuid]);

        $params = [
            'dataProvider' => $dataProvider,
            'result' => $result,
            'properties' => ResultProperty::find()
                ->where(['result_uuid' => $result_uuid])
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
     * @param string $result_uuid
     * @param string $field_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($result_uuid, $field_uuid)
    {
        $result = Result::findOne($result_uuid);

        if (!$result) {
            throw new HttpException(404, 'Form result not found.');
        }

        $field = Field::findOne(['uuid' => $field_uuid, 'form_uuid' => $result->form_uuid]);
        if (!$field) {
            throw new HttpException(404, 'Invalid field identifier.');
        }

        $params = [
            'result_uuid' => $result_uuid,
            'field_uuid' => $field_uuid,
        ];

        /* @var ResultProperty $model */
        $model = ResultProperty::findOne($params) ?: new ResultProperty($params);

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
