<?php
namespace users\modules\admin\modules\fields\tests\fixtures;

use users\modules\admin\modules\fields\models\FieldValue;
use yii\test\ActiveFixture;

/**
 * Class FieldValueFixture
 *
 * @package users\modules\admin\modules\fields\tests\fixtures
 */
class FieldValueFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = FieldValue::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/fields_values.php';
    /**
     * @var array
     */
    public $depends = [
        FieldFixture::class
    ];
}