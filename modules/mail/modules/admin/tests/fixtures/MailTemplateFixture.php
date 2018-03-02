<?php
namespace mail\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class MailTemplateFixture
 *
 * @package mail\modules\admin\tests\fixtures
 */
class MailTemplateFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'mail\modules\admin\models\Template';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/templates.php';
    /**
     * @var array
     */
    public $depends = [
        'mail\modules\admin\tests\fixtures\MailTypeFixture'
    ];
}