<?php

namespace forms\models;

use yii\db\ActiveRecord;

/**
 * Class FormEvent
 * @property string $form_uuid
 * @property string $type_uuid
 *
 * @package forms\models
 */
class FormEvent extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%forms_events}}';
    }
}