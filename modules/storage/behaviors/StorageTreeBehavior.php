<?php

namespace storage\behaviors;

use app\models\Workflow;
use storage\helpers\FileHelper;
use storage\models\Storage;
use storage\models\StorageFile;
use storage\models\StorageTree;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class StorageTreeBehavior
 * @package storage\behaviors
 */
class StorageTreeBehavior extends Behavior
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
        /* @var StorageTree $owner */
        $owner = $this->owner;

        // collecting all ancestor items to delete
        $this->collect($owner);

        /* @var StorageTree[] $children */
        $children = $owner->children()->all();
        foreach ($children as $child) {
            $this->collect($child);
        }
    }

    /**
     * @param StorageTree $owner
     */
    protected function collect(StorageTree $owner)
    {
        $this->_delete['Storage'][] = $owner->storage_uuid;
        if ($owner->storage_uuid) {
            $this->_delete['Workflow'][] = $owner->storage->workflow_uuid;
        }

        foreach ($owner->storage->versions as $version) {
            $this->_delete['StorageFile'][] = $version->file_uuid;
            if ($version->workflow_uuid) {
                $this->_delete['Workflow'][] = $version->workflow_uuid;
            }

            $this->_delete['File'][] = $version->file_uuid;
        }
    }

    /**
     * Delete collected items.
     */
    public function afterDelete()
    {
        foreach ($this->_delete as $class => $items) {
            switch ($class) {
                case 'Storage':
                    Storage::deleteAll(['uuid' => $items]);
                    break;
                case 'StorageFile':
                    StorageFile::deleteAll(['uuid' => $items]);
                    break;
                case 'Workflow':
                    Workflow::deleteAll(['uuid' => $items]);
                    break;
                case 'File':
                    FileHelper::removeFile($items);
                    break;
                default:
                    break;
            }
        }
    }
}
