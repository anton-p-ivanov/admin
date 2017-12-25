<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class AddressType
 *
 * @property string $uuid
 * @property string $title
 * @property string $sort
 * @property boolean $default
 * @property string $workflow_uuid
 *
 * @package app\models
 */
class AddressType extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%addresses_types}}';
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (strpos($name, 'title') === 0) {
            if (\Yii::$app->language !== \Yii::$app->sourceLanguage) {
                $name .= '_' . \Yii::$app->language;
            }
        }

        return parent::__get($name);
    }

    /**
     * @return array
     */
    public static function getList()
    {
        $prefix = \Yii::$app->language !== \Yii::$app->sourceLanguage ? \Yii::$app->language : null;
        $titleField = 'title' . ($prefix ? '_' . $prefix : '');

        return self::find()
            ->select($titleField)
            ->indexBy('uuid')
            ->orderBy(['sort' => SORT_ASC, $titleField => SORT_ASC])
            ->column();
    }

    /**
     * @return AddressType|ActiveRecord
     */
    public static function getDefault()
    {
        return self::find()->where(['default' => true])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::className(), ['uuid' => 'workflow_uuid']);
    }
}
