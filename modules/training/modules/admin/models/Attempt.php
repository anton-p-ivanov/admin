<?php

namespace training\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;

/**
 * Class Attempt
 *
 * @package training\modules\admin\models
*/
class Attempt extends \training\models\Attempt
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::class;

        return $behaviors;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [

        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [

        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }
}