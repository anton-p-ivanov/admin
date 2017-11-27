<?php

namespace app\components\behaviors {

    use Ramsey\Uuid\Uuid;
    use yii\base\Behavior;
    use yii\db\ActiveRecord;

    /**
     * Class PrimaryKeyBehavior
     * @package app\components\behaviors
     */
    class PrimaryKeyBehavior extends Behavior
    {
        /**
         * @var string
         */
        public $attribute = 'uuid';
        /**
         * @var string
         */
        public $randomString = null;

        /**
         * Event handler
         */
        public function beforeSave()
        {
            /* @var ActiveRecord $owner */
            $owner = $this->owner;
            if ($owner->hasAttribute($this->attribute)) {
                $owner->setAttribute($this->attribute, $this->generatePrimaryKey());
            }
        }

        /**
         * @return string
         */
        protected function generatePrimaryKey()
        {
            if ($this->randomString) {
                return Uuid::uuid5(Uuid::NAMESPACE_URL, $this->randomString);
            }

            return Uuid::uuid4();
        }

        /**
         * @inheritdoc
         */
        public function events()
        {
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave'
            ];
        }
    }

}