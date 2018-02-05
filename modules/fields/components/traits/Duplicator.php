<?php

namespace fields\components\traits;

use fields\models\FieldValidator;
use fields\models\FieldValue;

/**
 * Trait Duplicator
 *
 * @package fields\components\traits
 */
trait Duplicator
{
    /**
     * @param FieldValidator $validator
     * @param string $uuid
     * @return bool
     */
    protected function duplicateValidator(FieldValidator $validator, $uuid)
    {
        $clone = $validator->duplicate();
        $clone->field_uuid = $uuid;

        return $clone->save();
    }

    /**
     * @param FieldValue $value
     * @param string $uuid
     * @return bool
     */
    protected function duplicateValue(FieldValue $value, $uuid)
    {
        $clone = $value->duplicate();
        $clone->field_uuid = $uuid;

        return $clone->save();
    }
}