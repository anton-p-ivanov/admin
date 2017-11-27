<?php
namespace forms\models;

use app\models\UserSettings;
use yii\helpers\Json;

/**
 * Class FormSettings
 *
 * @package forms\models
 */
class FormSettings extends UserSettings
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
    protected static $_moduleName = 'forms';
    /**
     * @var string
     */
    protected static $_settingName = 'forms.index';

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'showDescription' => \Yii::t('forms', 'Show descriptions under file/folder if available.'),
            'sortBy' => \Yii::t('forms', 'Sort by'),
            'sortOrder' => \Yii::t('forms', 'Sort order'),
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

        $this->module = self::$_moduleName;
        $this->name = self::$_settingName;

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
            'title' => \Yii::t('forms', 'Title'),
            'code' => \Yii::t('forms', 'Code'),
            'active' => \Yii::t('forms', 'In use'),
            'results' => \Yii::t('forms', 'Results'),
            'sort' => \Yii::t('forms', 'Sort'),
            'workflow.modified' => \Yii::t('forms', 'Modified')
        ];
    }

    /**
     * @return array
     */
    public static function getSortOrder()
    {
        return [
            SORT_ASC => \Yii::t('forms', 'Ascending'),
            SORT_DESC => \Yii::t('forms', 'Descending'),
        ];
    }
}
