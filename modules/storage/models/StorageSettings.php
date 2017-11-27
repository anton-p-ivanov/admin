<?php
namespace storage\models;

use app\models\UserSettings;
use yii\helpers\Json;

/**
 * Class StorageSettings
 *
 * @package storage\models
 */
class StorageSettings extends UserSettings
{
    /**
     * @var bool
     */
    public $showDescription = true;
    /**
     * @var string
     */
    public $sortBy = 'storage.title';
    /**
     * @var int
     */
    public $sortOrder = SORT_ASC;
    /**
     * @var string
     */
    protected static $_moduleName = 'storage';
    /**
     * @var string
     */
    protected static $_settingName = 'storage.index';

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'showDescription' => \Yii::t('storage', 'Show descriptions under file/folder if available.'),
            'sortBy' => \Yii::t('storage', 'Sort by'),
            'sortOrder' => \Yii::t('storage', 'Sort order'),
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['showDescription', 'boolean'],
            ['sortBy', 'in', 'range' => array_keys(self::getSortFields())],
            ['sortOrder', 'in', 'range' => array_keys(self::getSortOrder())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        parent::afterValidate();

        $this->module = 'storage';
        $this->name = 'storage.index';

        $this->value = Json::encode([
            'showDescription' => (bool)$this->showDescription,
            'sortBy' => $this->sortBy,
            'sortOrder' => (int)$this->sortOrder
        ]);
    }

    /**
     * @return array
     */
    public static function getSortFields()
    {
        return [
            'storage.title' => \Yii::t('storage', 'File/folder'),
            'storage.file.size' => \Yii::t('storage', 'Size'),
            'storage.workflow.created.fullname' => \Yii::t('storage', 'Owner'),
            'storage.workflow.modified_date' => \Yii::t('storage', 'Uploaded')
        ];
    }

    /**
     * @return array
     */
    public static function getSortOrder()
    {
        return [
            SORT_ASC => \Yii::t('storage', 'Ascending'),
            SORT_DESC => \Yii::t('storage', 'Descending'),
        ];
    }
}
