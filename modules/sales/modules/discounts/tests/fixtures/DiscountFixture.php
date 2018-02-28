<?php
namespace sales\modules\discounts\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class DiscountFixture
 *
 * @package sales\modules\discounts\tests\fixtures
 */
class DiscountFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'sales\modules\discounts\models\Discount';
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