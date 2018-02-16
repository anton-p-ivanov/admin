<?php

namespace catalogs\behaviors;

use app\models\Workflow;
use catalogs\models\Element;
use catalogs\models\ElementTree;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class ElementTreeBehavior
 *
 * @package catalogs\behaviors
 */
class ElementTreeBehavior extends Behavior
{
    /**
     * @var array Collection of items to be deleted
     */
    private $_delete = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete'
        ];
    }

    /**
     * Collect items to delete.
     */
    public function beforeDelete()
    {
        /* @var ElementTree $owner */
        $owner = $this->owner;

        // collecting all ancestor items to delete
        $this->collect($owner);

        /* @var ElementTree[] $children */
        $children = $owner->children()->all();
        foreach ($children as $child) {
            $this->collect($child);
        }
    }

    /**
     * @param ElementTree $owner
     */
    protected function collect(ElementTree $owner)
    {
        $this->_delete['Element'][] = $owner->element_uuid;
        if ($owner->element_uuid) {
            $this->_delete['Workflow'][] = $owner->element->workflow_uuid;
        }
    }

    /**
     * Delete collected items.
     */
    public function afterDelete()
    {
        foreach ($this->_delete as $class => $items) {
            switch ($class) {
                case 'Element':
                    Element::deleteAll(['uuid' => $items]);
                    break;
                case 'Workflow':
                    Workflow::deleteAll(['uuid' => $items]);
                    break;
                default:
                    break;
            }
        }
    }
}
