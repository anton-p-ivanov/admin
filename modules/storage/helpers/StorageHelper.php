<?php

namespace storage\helpers;

use storage\models\StorageTree;

/**
 * Class StorageHelper
 * @package storage\helpers
 */
class StorageHelper
{
    /**
     * @param array $locations
     * @return string
     */
    public static function getLocationTitle(array $locations = []): string
    {
        $title = \Yii::t('storage', 'Root');
        if (!$locations) {
            return $title;
        }

        $location = StorageTree::findOne(['tree_uuid' => array_shift($locations)]);
        if ($location && $location->storage) {
            $title = $location->storage->title;
        }

        return $title;
    }
}