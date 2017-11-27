<?php
namespace storage\models;

use yii\db\ActiveRecord;

/**
 * Class StorageImage
 *
 * @property string $file_uuid
 * @property string $width
 * @property integer $height

 * @package storage\models
 */
class StorageImage extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%storage_images}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['file_uuid', 'exist', 'targetClass' => StorageFile::className(), 'targetAttribute' => 'uuid'],
            [['width', 'height'], 'integer', 'min' => 0]
        ];
    }
}
