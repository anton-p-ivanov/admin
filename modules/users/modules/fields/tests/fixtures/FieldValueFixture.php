<?php
namespace users\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FieldValueFixture
 * @package users\modules\fields\tests\fixtures
 */
class FieldValueFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'users\modules\fields\models\FieldValue';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/fields_values.php';
    /**
     * @var array
     */
    public $depends = [
        'users\modules\fields\tests\fixtures\FieldFixture'
    ];
}