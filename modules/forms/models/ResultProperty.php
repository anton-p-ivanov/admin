<?php

namespace forms\models;

use fields\models\Property;
use forms\modules\admin\modules\fields\models\Field;

/**
 * Class ResultProperty
 *
 * @property string $result_uuid
 * @property string $field_uuid
 * @property string $value
 *
 * @package forms\models
 */
class ResultProperty extends Property
{
    /**
     * @var \yii\db\ActiveRecord
     */
    public static $fieldModel = Field::class;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%forms_results_properties}}';
    }
}