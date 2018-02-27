<?php
namespace sales\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class DiscountFixture
 *
 * @package sales\modules\admin\tests\fixtures
 */
class DiscountFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'sales\modules\admin\models\Discount';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/discounts.php';
    /**
     * @var array
     */
    public $depends = [
        WorkflowFixture::class
    ];
}