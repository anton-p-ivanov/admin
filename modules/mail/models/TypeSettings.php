<?php
namespace mail\models;

use app\models\UserSettings;
use yii\helpers\Json;

/**
 * Class TypeSettings
 *
 * @package mail\models
 */
class TypeSettings extends UserSettings
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
    protected static $_moduleName = 'mail';
    /**
     * @var string
     */
    protected static $_settingName = 'types.index';

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('mail', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'showDescription' => 'Show descriptions with mailing type name.',
            'sortBy' => 'Sort by',
            'sortOrder' => 'Sort order',
        ];

        return array_map('self::t', $labels);
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
        $fields = [
            'title' => 'Title',
            'code' => 'Code',
            'workflow.modified' => 'Modified'
        ];

        return array_map('self::t', $fields);
    }

    /**
     * @return array
     */
    public static function getSortOrder()
    {
        $orders = [
            SORT_ASC => 'Ascending',
            SORT_DESC => 'Descending',
        ];

        return array_map('self::t', $orders);
    }
}
