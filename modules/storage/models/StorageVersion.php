<?php
namespace storage\models;

use app\components\behaviors\WorkflowBehavior;
use app\models\Workflow;
use yii\db\ActiveRecord;

/**
 * Class StorageVersion
 *
 * @property string $file_uuid
 * @property string $storage_uuid
 * @property string $workflow_uuid
 * @property boolean $active
 *
 * @property StorageFile $file
 * @property Storage $storage
 * @property Workflow $workflow
 *
 * @package storage\models
 */
class StorageVersion extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%storage_versions}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'file.name' => 'File name',
            'file.size' => 'Size',
            'file.type' => 'Type',
            'workflow.created_date' => 'Uploaded'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @param string $label
     * @return string
     */
    public static function t($label)
    {
        return \Yii::t('storage', $label);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['workflow'] = WorkflowBehavior::className();

        return $behaviors;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['active', 'boolean'],
        ];
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Deactivates previous versions
        $this->deactivatePrevious();
    }

    /**
     * Deactivates previous versions for current `storage_uuid`.
     * @return int
     */
    protected function deactivatePrevious(): int
    {
        return self::updateAll(['active' => false], '`storage_uuid` = :storage_uuid AND `file_uuid` != :file_uuid', [
            ':file_uuid' => $this->file_uuid,
            ':storage_uuid' => $this->storage_uuid
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(StorageFile::className(), ['uuid' => 'file_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorage()
    {
        return $this->hasOne(Storage::className(), ['uuid' => 'storage_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::className(), ['uuid' => 'workflow_uuid']);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->active === true;
    }
}
