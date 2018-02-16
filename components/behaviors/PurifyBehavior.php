<?php

namespace app\components\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\HtmlPurifier;

/**
 * Class PurifyBehavior
 * @package app\components\behaviors
 */
class PurifyBehavior extends Behavior
{
    /**
     * @var array Attributes list to be purified
     */
    public $attributes;
    /**
     * @var array
     */
    public $config;

    /**
     * Event handler
     */
    public function beforeValidate()
    {
        /* @var ActiveRecord $owner */
        $owner = $this->owner;

        if (!$this->attributes) {
            $this->attributes = $owner->attributes();
        }

        array_walk($this->attributes, function ($attribute) use ($owner) {
            $config = $this->config;
            if (is_array($attribute)) {
                $attribute = $attribute['attribute'];
                $config = $attribute['config'];
            }

            if ($owner->hasAttribute($attribute)) {
                $this->owner->$attribute = HtmlPurifier::process($owner->$attribute, $config);
            }
        });
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }
}
