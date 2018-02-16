<?php

namespace catalogs\helpers;

use catalogs\models\ElementTree;

/**
 * Class ElementHelper
 *
 * @package catalogs\helpers
 */
class ElementHelper
{
    /**
     * @param array $locations
     * @return string
     */
    public static function getLocationTitle(array $locations = []): string
    {
        $title = \Yii::t('catalogs/elements', 'Catalog root');
        if (!$locations) {
            return $title;
        }

        $location = ElementTree::findOne(['tree_uuid' => array_shift($locations)]);
        if ($location && $location->element) {
            $title = $location->element->title;
        }

        return $title;
    }
}