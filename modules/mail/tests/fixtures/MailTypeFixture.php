<?php
namespace mail\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class MailTypeFixture
 * @package mail\tests\fixtures
 */
class MailTypeFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'mail\models\Type';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/types.php';
    /**
     * @var array
     */
    public $depends = [
        'workflow' => 'mail\tests\fixtures\WorkflowFixture'
    ];
}