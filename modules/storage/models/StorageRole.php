<?php
namespace storage\models;

use yii\db\ActiveRecord;

/**
 * Class StorageRole
 *
 * @property string $storage_uuid
 * @property string $auth_item
 *
 * @package storage\models
 */
class StorageRole extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%storage_roles}}';
    }

    /**
     * @param string $storage_uuid
     * @return array
     */
    public static function getList($storage_uuid): array
    {
        return StorageRole::find()->where(['storage_uuid' => $storage_uuid])->select('auth_item')->column();
    }
}
