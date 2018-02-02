<?php

namespace users\modules\admin\modules\fields\controllers;

use users\modules\admin\modules\fields\models\Field;
use users\modules\admin\modules\fields\models\FieldValidator;
use users\modules\admin\modules\fields\models\FieldValue;

/**
 * Class FieldsController
 *
 * @package users\modules\admin\modules\fields\controllers
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

        $viewFile = '@users/modules/admin/modules/fields/views/fields/index';

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial($viewFile, $params);
        }

        return $this->render($viewFile, $params);
    }
}
