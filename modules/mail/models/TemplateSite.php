<?php

namespace mail\models;

use yii\db\ActiveRecord;

/**
 * Class TemplateSite
 *
 * @property string $template_uuid
 * @property string $site_uuid
 *
 * @package mail\models
 */
class TemplateSite extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%mail_templates_sites}}';
    }
}