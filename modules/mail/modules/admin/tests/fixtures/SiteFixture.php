<?php
namespace mail\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class SiteFixture
 * modules\admin\
 * @package mail\modules\admin\tests\fixtures
 */
class SiteFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'app\models\Site';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/sites.php';
}