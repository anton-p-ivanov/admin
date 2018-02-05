<?php

namespace forms\modules\admin\modules\fields\validators;

use forms\modules\admin\modules\fields\models\Field;
use yii\validators\Validator;

/**
 * Class UniqueCodeValidator
 *
 * @package forms\modules\admin\modules\fields\validators
 */
class UniqueCodeValidator extends Validator
{
    /**
     * @var string
     */
    public $message = 'Code `{code}` is already in use.';

    /**
     * @param Field $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $count = Field::find()->where(['form_uuid' => $model->form_uuid, 'code' => $model->$attribute])->count();
        if ($count > 0) {
            $this->addError($model, $attribute, Field::t($this->message, ['code' => $model->$attribute]));
        }
    }
}