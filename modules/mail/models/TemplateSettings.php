<?php
namespace mail\models;

use app\models\UserSettings;
use yii\helpers\Json;

/**
 * Class TemplateSettings
 *
 * @package mail\models
 */
class TemplateSettings extends UserSettings
{
    /**
     * @var string
     */
    public $sortBy = 'workflow.modified_date';
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
    protected static $_settingName = 'templates.index';

    /**
     * @param $message
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
    public function attributeLabels(): array
    {
        $labels = [
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
            'subject' => 'Subject',
            'from' => 'From',
            'to' => 'To',
            'workflow.modified_date' => 'Modified'
        ];

        return array_map('self::t', $fields);
    }

    /**
     * @return array
     */
    public static function getSortOrder(): array
    {
        $order = [
            SORT_ASC => 'Ascending',
            SORT_DESC => 'Descending',
        ];

        return array_map('self::t', $order);
    }
}
