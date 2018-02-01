<?php

namespace catalogs\modules\admin\modules\fields\components\traits;

use catalogs\modules\admin\modules\fields\models\Field;
use catalogs\modules\admin\modules\fields\models\Group;

/**
 * Trait Duplicator
 *
 * @package catalogs\modules\admin\modules\fields\components\traits
 */
trait Duplicator
{
    /**
     * @var array
     */
    private $_groups = [];

    /**
     * @param Group $group
     * @param string $uuid
     * @return bool
     */
    protected function duplicateGroup(Group $group, $uuid)
    {
        $clone = $group->duplicate();
        $clone->catalog_uuid = $uuid;

        if ($result = $clone->save()) {
            $this->_groups[$group->uuid] = $clone->uuid;
        }

        return $result;
    }

    /**
     * @param Field $field
     * @param $uuid
     * @return bool
     */
    protected function duplicateField(Field $field, $uuid)
    {
        $clone = $field->duplicate();
        $clone->catalog_uuid = $uuid;
        $clone->group_uuid = array_key_exists($field->group_uuid, $this->_groups)
            ? $this->_groups[$field->group_uuid]
            : null;

        if ($result = $clone->save()) {
            foreach ($field->fieldValues as $value) {
                $this->duplicateValue($value, $clone->uuid);
            }

            foreach ($field->fieldValidators as $validator) {
                $this->duplicateValidator($validator, $clone->uuid);
            }
        }

        return $result;
    }
}