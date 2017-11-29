<?php

namespace mail\models;

use yii\db\ActiveRecord;

/**
 * Class TemplateType
 *
 * @property string $template_uuid
 * @property string $type_uuid
 *
 * @package mail\models
 */
class TemplateType extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%mail_templates_types}}';
    }
}