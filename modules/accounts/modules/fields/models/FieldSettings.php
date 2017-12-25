<?php
namespace accounts\modules\fields\models;

use app\models\UserSettings;
use yii\helpers\Json;

/**
 * Class FieldSettings
 *
 * @package accounts\modules\fields\models
 */
class FieldSettings extends UserSettings
{
    /**
     * @var string
     */
    public $sortBy = 'label';
    /**
     * @var int
     */
    public $sortOrder = SORT_ASC;
    /**
     * @var string
     */
    protected static $_moduleName = 'accounts.fields';
    /**
     * @var string
     */
    protected static $_settingName = 'fields.index';

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('fields', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $labels =  [
            'sortBy' => 'Sort by',
            'sortOrder' => 'Sort order',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
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
            'sortBy' => $this->sortBy,
            'sortOrder' => (int)$this->sortOrder
        ]);
    }

    /**
     * @return array
     */
    public static function getSortFields(): array
    {
        $fields = [
            'label' => 'Label',
            'multiple' => 'Multiple',
            'active' => 'Active',
            'sort' => 'Sort',
            'workflow.modified' => 'Modified'
        ];

        return array_map('self::t', $fields);
    }

    /**
     * @return array
     */
    public static function getSortOrder(): array
    {
        $orders = [
            SORT_ASC => 'Ascending',
            SORT_DESC => 'Descending',
        ];

        return array_map('self::t', $orders);
    }
}
