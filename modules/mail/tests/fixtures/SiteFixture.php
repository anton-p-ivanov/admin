<?php
namespace mail\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class SiteFixture
 * @package mail\tests\fixtures
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
    public $dataFile = __DIR__ . '/data/sites.php';
}