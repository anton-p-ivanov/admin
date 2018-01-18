<?php
namespace admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class SitesFixture
 * @package admin\tests\fixtures
 */
class SitesFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'admin\models\Site';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/sites.php';
}