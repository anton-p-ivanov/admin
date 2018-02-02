<?php

namespace accounts\modules\admin\modules\fields\controllers;

use accounts\modules\admin\modules\fields\models\Field;
use accounts\modules\admin\modules\fields\models\FieldValidator;
use accounts\modules\admin\modules\fields\models\FieldValue;

/**
 * Class FieldsController
 *
 * @package accounts\modules\admin\modules\fields\controllers
 */
class FieldsController extends \fields\controllers\FieldsController
{
    /**
     * @var string|\yii\db\ActiveRecord
     */
    public $modelClass = Field::class;

    /**
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = Field::search();

        $params = [
            'dataProvider' => $dataProvider,
            'validators' => FieldValidator::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('field_uuid')
                ->indexBy('field_uuid')->column(),
            'values' => FieldValue::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('field_uuid')
                ->indexBy('field_uuid')->column(),
        ];

        $viewFile = '@accounts/modules/admin/modules/fields/views/fields/index';

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial($viewFile, $params);
        }

        return $this->render($viewFile, $params);
    }
}
