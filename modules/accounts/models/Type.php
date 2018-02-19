<?php
namespace accounts\models;

use app\components\behaviors\PrimaryKeyBehavior;
use i18n\components\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Type
 *
 * @property string $uuid
 * @property string $title
 * @property int $sort
 * @property boolean $default
 *
 * @package accounts\models
 */
class Type extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_types}}';
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return static::find()
            ->joinWith('translation')
            ->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC])
            ->select('title')
            ->indexBy('uuid')
            ->column();
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('accounts/types', $message, $params);
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return (int) $this->default === 1;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::class;
        $behaviors['ml'] = ArrayHelper::merge($behaviors['ml'], [
            'langForeignKey' => 'type_uuid',
            'tableName' => '{{%accounts_types_i18n}}',
            'attributes' => ['title']
        ]);

        return $behaviors;
    }
}
