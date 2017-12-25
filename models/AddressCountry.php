<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class AddressCountry
 *
 * @property string $code
 * @property string $title
 *
 * @package app\models
 */
class AddressCountry extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%addresses_countries}}';
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
}
