<?php
namespace forms\modules\admin\modules\fields\tests\fixtures;

use forms\modules\admin\modules\fields\models\FieldValue;
use forms\modules\admin\tests\fixtures\FormFieldFixture;
use yii\test\ActiveFixture;

/**
 * Class FieldValueFixture
 *
 * @package forms\modules\admin\modules\fields\tests\fixtures
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
        FormFieldFixture::class
    ];
}