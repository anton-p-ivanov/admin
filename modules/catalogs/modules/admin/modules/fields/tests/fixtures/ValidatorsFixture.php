<?php
namespace catalogs\modules\admin\modules\fields\tests\fixtures;

use catalogs\modules\admin\modules\fields\models\FieldValidator;
use yii\test\ActiveFixture;

/**
 * Class ValidatorsFixture
 *
 * @package catalogs\modules\admin\modules\fields\tests\fixtures
 */
class ValidatorsFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = FieldValidator::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/validators.php';
    /**
     * @var array
     */
    public $depends = [
        FieldFixture::class
    ];
}