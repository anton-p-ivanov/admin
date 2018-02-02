<?php
namespace users\modules\admin\modules\fields\tests\fixtures;

use users\modules\admin\modules\fields\models\Field;
use yii\test\ActiveFixture;

/**
 * Class FieldFixture
 *
 * @package users\modules\admin\modules\fields\tests\fixtures
 */
class FieldFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = Field::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/fields.php';
    /**
     * @var array
     */
    public $depends = [
        WorkflowFixture::class
    ];
}