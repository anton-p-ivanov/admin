<?php
namespace partnership\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class StatusI18NFixture
 * @package partnership\tests\fixtures
 */
class StatusI18NFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $tableName = '{{%partnership_statuses_i18n}}';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/statuses_i18n.php';
}