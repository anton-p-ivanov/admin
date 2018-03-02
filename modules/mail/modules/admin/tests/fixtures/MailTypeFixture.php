<?php
namespace mail\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class MailTypeFixture
 *
 * @package mail\modules\admin\tests\fixtures
 */
class MailTypeFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'mail\modules\admin\models\Type';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/types.php';
    /**
     * @var array
     */
    public $depends = [
        'mail\modules\admin\tests\fixtures\WorkflowFixture'
    ];
}