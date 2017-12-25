<?php
namespace accounts\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FieldValueFixture
 * @package accounts\modules\fields\tests\fixtures
 */
class FieldValueFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'accounts\modules\fields\models\FieldValue';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/fields_values.php';
    /**
     * @var array
     */
    public $depends = [
        'accounts\modules\fields\tests\fixtures\FieldFixture'
    ];
}