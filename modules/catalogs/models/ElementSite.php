<?php

namespace catalogs\models;

use yii\db\ActiveRecord;

/**
 * Class ElementSite
 *
 * @property string $element_uuid
 * @property string $site_uuid
 *
 * @package catalogs\models
 */
class ElementSite extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs_elements_sites}}';
    }
}
