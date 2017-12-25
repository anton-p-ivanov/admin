<?php
namespace accounts\models;

use app\models\UserSettings;
use yii\helpers\Json;

/**
 * Class AccountSettings
 *
 * @package accounts\models
 */
class AccountSettings extends UserSettings
{
    /**
     * @var bool
     */
    public $showDescription = true;
    /**
     * @var string
     */
    public $sortBy = 'title';
    /**
     * @var int
     */
    public $sortOrder = SORT_ASC;
    /**
     * @var string
     */
    protected static $_moduleName = 'accounts';
    /**
     * @var string
     */
    protected static $_settingName = 'accounts.index';

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'sortBy' => \Yii::t('accounts', 'Sort by'),
            'sortOrder' => \Yii::t('accounts', 'Sort order'),
            'showDescription' => \Yii::t('accounts', 'Show description'),
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['sortBy', 'in', 'range' => array_keys(self::getSortFields())],
            ['sortOrder', 'in', 'range' => array_keys(self::getSortOrder())],
            ['showDescription', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        parent::afterValidate();

        $this->module = self::$_moduleName;
        $this->name = self::$_settingName;

        $this->value = Json::encode([
            'sortBy' => $this->sortBy,
            'sortOrder' => (int)$this->sortOrder,
            'showDescription' => $this->showDescription
        ]);
    }

    /**
     * @return array
     */
    public static function getSortFields()
    {
        return [
            'title' => \Yii::t('accounts', 'Title'),
            'active' => \Yii::t('accounts', 'Active'),
            'sort' => \Yii::t('accounts', 'Sort'),
            'parent_uuid' => \Yii::t('accounts', 'Headquarter'),
            'workflow.modified' => \Yii::t('accounts', 'Modified')
        ];
    }

    /**
     * @return array
     */
    public static function getSortOrder()
    {
        return [
            SORT_ASC => \Yii::t('accounts', 'Ascending'),
            SORT_DESC => \Yii::t('accounts', 'Descending'),
        ];
    }
}
