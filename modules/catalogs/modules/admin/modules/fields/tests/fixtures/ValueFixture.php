<?php
namespace catalogs\modules\admin\modules\fields\tests\fixtures;

use catalogs\modules\admin\modules\fields\models\FieldValue;
use yii\test\ActiveFixture;

/**
 * Class ValueFixture
 * @package catalogs\modules\admin\modules\fields\tests\fixtures
 */
class ValueFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = FieldValue::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/values.php';
    /**
     * @var array
     */
    public $depends = [
        FieldFixture::class
    ];
}