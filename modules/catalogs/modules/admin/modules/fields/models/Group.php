<?php

namespace catalogs\modules\admin\modules\fields\models;

use catalogs\modules\admin\models\Catalog;

/**
 * Class Group

 * @property string $catalog_uuid
 * @property Catalog $catalog
 *
 * @package catalogs\modules\admin\modules\fields\models
 */
class Group extends \fields\models\Group
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs_fields_groups}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['catalog_uuid'] = 'Catalog';

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = parent::attributeHints();
        $hints['catalog_uuid'] = 'Select one of available catalogs.';

        return array_map('self::t', $hints);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(Catalog::class, ['uuid' => 'catalog_uuid']);
    }

    /**
     * @return Group
     */
    public function duplicate()
    {
        /* @var Group $clone */
        $clone = parent::duplicate();
        $clone->catalog_uuid = $this->catalog_uuid;

        return $clone;
    }
}